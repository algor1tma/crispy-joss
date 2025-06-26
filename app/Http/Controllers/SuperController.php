<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Karyawan;
use App\Models\Super;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class SuperController extends Controller
{
    public function index()
    {
        $myData = Super::where('user_id', auth()->id())->first();

        // Get statistics
        $totalUsers = User::whereIn('roles', ['admin', 'karyawan'])->count();
        $totalAdmins = User::where('roles', 'admin')->count();
        $totalKaryawan = User::where('roles', 'karyawan')->count();

        // Get recent users
        $recentUsers = User::whereIn('roles', ['admin', 'karyawan'])
            ->with(['admin', 'karyawan'])
            ->latest()
            ->take(5)
            ->get();

        return view('pages.super.dashboard', compact('myData', 'totalUsers', 'totalAdmins', 'totalKaryawan', 'recentUsers'));
    }

    public function manageUsers()
    {
        $users = User::whereIn('roles', ['admin', 'karyawan'])
            ->with(['admin', 'karyawan'])
            ->latest()
            ->get();

        return view('pages.super.manage-users', compact('users'));
    }

    public function createUser()
    {
        return view('pages.super.create-user');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            // 'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'roles' => 'required|in:admin,karyawan',
            'nama' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                // 'email' => $request->email,
                'password' => Hash::make($request->password),
                'roles' => $request->roles,
                'username' => $request->nama, 
            ]);

            // Create profile based on role
            if ($request->roles === 'admin') {
                Admin::create([
                    'user_id' => $user->id,
                    'nama' => $request->nama,
                    'no_telp' => $request->no_telp,
                    'alamat' => $request->alamat,
                ]);
            } else {
                Karyawan::create([
                    'user_id' => $user->id,
                    'nama' => $request->nama,
                    'no_telp' => $request->no_telp,
                    'alamat' => $request->alamat,
                ]);
            }

            DB::commit();
            Alert::success('Berhasil', 'User berhasil ditambahkan');
            return redirect()->route('super.manage-users');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Gagal', 'Terjadi kesalahan saat menambahkan user');
            return back()->withInput();
        }
    }

    public function editUser($id)
    {
        $user = User::with(['admin', 'karyawan'])->findOrFail($id);

        if (!in_array($user->roles, ['admin', 'karyawan'])) {
            Alert::error('Gagal', 'User tidak dapat diedit');
            return redirect()->route('super.manage-users');
        }

        return view('pages.super.edit-user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            // 'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'roles' => 'required|in:admin,karyawan',
            'nama' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Update user
            $userData = [
                // 'email' => $request->email,
                'roles' => $request->roles,
                'username' => $request->nama, 
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // Update profile based on current role
            $profileData = [
                'nama' => $request->nama,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
            ];

            // If role changed, delete old profile and create new one
            if ($user->roles !== $request->roles) {
                if ($user->admin) {
                    $user->admin->delete();
                }
                if ($user->karyawan) {
                    $user->karyawan->delete();
                }

                if ($request->roles === 'admin') {
                    Admin::create(array_merge($profileData, ['user_id' => $user->id]));
                } else {
                    Karyawan::create(array_merge($profileData, ['user_id' => $user->id]));
                }
            } else {
                // Update existing profile
                if ($user->admin) {
                    $user->admin->update($profileData);
                } elseif ($user->karyawan) {
                    $user->karyawan->update($profileData);
                }
            }

            DB::commit();
            Alert::success('Berhasil', 'User berhasil diperbarui');
            return redirect()->route('super.manage-users');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Gagal', 'Terjadi kesalahan saat memperbarui user');
            return back()->withInput();
        }
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if (!in_array($user->roles, ['admin', 'karyawan'])) {
            Alert::error('Gagal', 'User tidak dapat dihapus');
            return redirect()->route('super.manage-users');
        }

        try {
            $user->delete();
            Alert::success('Berhasil', 'User berhasil dihapus');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat menghapus user');
        }

        return redirect()->route('super.manage-users');
    }
}
