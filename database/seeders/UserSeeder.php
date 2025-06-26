<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Karyawan;
use App\Models\Super;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superUser = User::create([
            'email' => 'super@admin.com',
            'username' => 'superadmin',
            'password' => Hash::make('password'),
            'roles' => 'super',
        ]);

        Super::create([
            'user_id' => $superUser->id,
            'nama' => 'Super Administrator',
            'no_telp' => '081234567890',
            'alamat' => 'Jl. Admin Super No. 1, Jakarta',
        ]);

        $admin = User::create([
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'roles' => 'admin',
        ]);

        Admin::create([
            'user_id' => $admin->id,
            'nama' => 'Administrator',
            'no_telp' => '08123456789',
            'alamat' => 'Jl. Admin No. 1',
        ]);

        $karyawan = User::create([
            'email' => 'karyawan@karyawan.com',
            'username' => 'karyawan',
            'password' => Hash::make('password'),
            'roles' => 'karyawan',
        ]);

        Karyawan::create([
            'user_id' => $karyawan->id,
            'nama' => 'Nama Karyawan',
            'no_telp' => '08987654321',
            'alamat' => 'Jl. Karyawan No. 2',
            'foto' => 'default.jpg',
        ]);
    }
}
