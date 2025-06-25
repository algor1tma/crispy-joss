<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Karyawan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // Create admin user
    $user = User::create([
        'email' => 'admin@admin.com',
        'password' => Hash::make('password'),
        'roles' => 'admin',
    ]);

    // Create admin profile
    Admin::create([
        'user_id' => $user->id,
        'nama' => 'Administrator',
        'jenis_kelamin' => 'L',
        'no_telp' => '08123456789',
        'alamat' => 'Jl. Admin No. 1',
    ]);

    // Create karyawan user
    $karyawan = User::create([
        'email' => 'karyawan@karyawan.com',
        'password' => Hash::make('password'),
        'roles' => 'karyawan',
    ]);

    // Create karyawan profile
    Karyawan::create([
        'user_id' => $karyawan->id,
        'nama' => 'Nama Karyawan',
        'no_telp' => '08987654321',
        'alamat' => 'Jl. Karyawan No. 2',
        'foto' => 'default.jpg',
    ]);
}
}
