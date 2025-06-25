<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\SettingSistem;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(UserSeeder::class);

        // Create default admin user
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
    }
}
