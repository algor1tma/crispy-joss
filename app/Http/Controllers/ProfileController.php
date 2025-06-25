<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        // Validasi input
        $rules = [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ];

        // Tambah validasi foto untuk karyawan
        if ($user->roles === 'karyawan') {
            $rules['foto'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        $request->validate($rules);

        // Update user email dan password
        User::where('id', $user->id)
            ->update([
                'email' => $request->email,
                'password' => $request->filled('password') ? Hash::make($request->password) : $user->password
            ]);

        // Update profil berdasarkan role
        if ($user->roles === 'admin') {
            Admin::updateOrCreate(
                ['user_id' => $user->id],
                ['nama' => $request->nama]
            );
        } elseif ($user->roles === 'karyawan') {
            $karyawan = Karyawan::where('user_id', $user->id)->first();
            
            $updateData = ['nama' => $request->nama];
            
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($karyawan && $karyawan->foto) {
                    $oldPhotoPath = public_path('img/DataKaryawan/' . $karyawan->foto);
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }

                // Generate nama file yang unik
                $foto = $request->file('foto');
                $extension = $foto->getClientOriginalExtension();
                $fotoName = 'profile_' . Str::random(10) . '_' . time() . '.' . $extension;

                // Upload foto baru ke public path
                $foto->move(public_path('img/DataKaryawan'), $fotoName);
                
                $updateData['foto'] = $fotoName;
            }

            // Add alamat to updateData if it exists in request
            if ($request->has('alamat')) {
                $updateData['alamat'] = $request->alamat;
            }

            Karyawan::updateOrCreate(
                ['user_id' => $user->id],
                $updateData
            );
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }
} 