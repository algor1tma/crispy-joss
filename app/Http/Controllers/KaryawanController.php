<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function index()
    {
        // Mengambil data karyawan beserta data user
        $karyawans = Karyawan::with(['user' => function($query) {
            $query->withTrashed();
        }])->get();

        // Menampilkan data karyawan di view
        return view('pages.manajemen.karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        // Menampilkan form untuk menambahkan karyawan
        return view('pages.manajemen.karyawan.create');
    }

    public function store(Request $request)
    {
        // Validasi input data
        $validated = $request->validate([
            'email' => 'required|unique:users,email,NULL,id,deleted_at,NULL',
            'nama' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'password' => 'required|min:6'
        ], [
            'email.unique' => 'Email sudah digunakan.',
            'nama.required' => 'Nama harus diisi.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        try {
            DB::beginTransaction();

            // Membuat data user terlebih dahulu
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($request->password),
                'roles' => 'karyawan',
            ]);

            // Membuat data karyawan
            $karyawan = new Karyawan([
                'nama' => $validated['nama'],
                'no_telp' => $validated['no_telp'],
                'alamat' => $request->alamat,
            ]);

            // Menyimpan foto karyawan jika ada
            if ($request->hasFile('imageKaryawan')) {
                $foto = $request->file('imageKaryawan');
                $filegambar = time() . "_" . $foto->getClientOriginalName();
                $foto->move(public_path('img/DataKaryawan'), $filegambar);  // Menyimpan gambar di folder public/img/DataKaryawan
                $karyawan->foto = $filegambar;  // Menyimpan nama file gambar ke database
            }

            // Menyimpan data karyawan yang terhubung dengan user
            $user->karyawan()->save($karyawan);

            DB::commit();
            Alert::success('Success', 'Berhasil Menambahkan Data');
            return redirect()->route('indexKaryawan');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Gagal menambahkan data: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        // Menampilkan form untuk mengedit data karyawan
        $karyawan = Karyawan::findOrFail($id);
        return view('pages.manajemen.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data input
        $karyawan = Karyawan::findOrFail($id);

        $validated = $request->validate([
            'email' => 'nullable|unique:users,email,' . $karyawan->user->id . ',id,deleted_at,NULL',
            'nama' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ], [
            'email.unique' => 'Email sudah digunakan.',
            'nama.required' => 'Nama harus diisi.',
        ]);

        // Menyimpan foto karyawan baru jika ada
        if ($request->hasFile('imageKaryawan')) {
            // Hapus foto lama jika ada
            if ($karyawan->foto && file_exists(public_path('img/DataKaryawan/' . $karyawan->foto))) {
                unlink(public_path('img/DataKaryawan/' . $karyawan->foto));  // Hapus foto lama dari folder
            }

            // Menyimpan foto baru
            $foto = $request->file('imageKaryawan');
            $filegambar = time() . "_" . $foto->getClientOriginalName();
            $foto->move(public_path('img/DataKaryawan'), $filegambar); // Menyimpan file di folder public/img/DataKaryawan
            $karyawan->foto = $filegambar;  // Update nama file foto di database
        }

        // Update data karyawan
        $karyawan->nama = $validated['nama'];
        $karyawan->no_telp = $validated['no_telp'];
        $karyawan->alamat = $request->alamat;
        $karyawan->save();

        // Update data email user jika berbeda
        if ($request->email && $karyawan->user->email !== $request->email) {
            $karyawan->user->email = $request->email;
            $karyawan->user->save();
        }

        Alert::success('Success', 'Berhasil Memperbarui Data');
        return redirect()->route('indexKaryawan');
    }

    public function delete(User $id)
    {
        try {
            DB::beginTransaction();

            $karyawan = $id->karyawan;
            if ($karyawan && $karyawan->foto) {
                // Hapus foto jika ada
                if (file_exists(public_path('img/DataKaryawan/' . $karyawan->foto))) {
                    unlink(public_path('img/DataKaryawan/' . $karyawan->foto));  // Hapus foto karyawan
                }
            }

            // Soft delete both user and karyawan
            $id->delete(); // Ini akan cascade ke karyawan karena foreign key constraint

            DB::commit();
            Alert::success('Success', 'Data karyawan berhasil dihapus.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Gagal menghapus data: ' . $e->getMessage());
            return back();
        }
    }
}
