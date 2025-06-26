<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth/login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|max:50|regex:/^[a-zA-Z0-9_]+$/',
            'password' => 'required|string|min:6',
        ], [
            'username.required' => 'Username harus diisi',
            'username.min' => 'Username minimal 3 karakter',
            'username.max' => 'Username maksimal 50 karakter',
            'username.regex' => 'Username hanya boleh berisi huruf, angka, dan underscore',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->roles) {
                Auth::logout();
                return back()->with('loginError', 'Akun tidak memiliki hak akses!');
            }

            // Redirect sesuai role
            Alert::success('Success', 'Login Berhasil');

            if ($user->roles === 'karyawan') {
                return redirect()->intended(route('produk.index'));
            }

            return redirect()->intended(route('indexDashboard'));
        }

        return back()->with('loginError', 'Username atau password salah!');
    }

    public function create(){
        // Hanya admin yang bisa mengakses halaman registrasi
        if (!Auth::check() || Auth::user()->roles !== 'admin') {
            Alert::error('Error', 'Anda tidak memiliki akses ke halaman ini!');
            return redirect()->route('indexDashboard');
        }
        return view('auth/registrasi');
    }

    public function store(Request $request){
        // Validasi akses
        if (!Auth::check() || Auth::user()->roles !== 'admin') {
            Alert::error('Error', 'Anda tidak memiliki akses untuk membuat akun!');
            return redirect()->route('indexDashboard');
        }

        $validated = $request->validate(
            [
                'email' => 'required|unique:users|email',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|string',
                'nama' => 'required|string|max:255',
                'no_telp' => 'required|string|max:15',
                'alamat' => 'required|string',
            ],
            [
                'email.unique' => 'Email telah terdaftar.',
                'email.email' => 'Format email tidak valid.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'password.min' => 'Password minimal 6 karakter.',
                'nama.required' => 'Nama karyawan wajib diisi.',
                'no_telp.required' => 'Nomor telepon wajib diisi.',
                'alamat.required' => 'Alamat wajib diisi.',
            ]
        );

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'roles' => 'karyawan', // Set role default sebagai karyawan
            'nama' => $validated['nama'],
            'no_telp' => $validated['no_telp'],
            'alamat' => $validated['alamat'],
        ]);

        Alert::success('Success', 'Akun karyawan berhasil dibuat');
        return redirect()->route('indexDashboard');
    }

    public function out()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('indexAuth');
    }
}
