<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        // Update last login timestamp
        $user->update([
            'last_login' => Carbon::now()
        ]);

        // Check if user is soft deleted
        if ($user->deleted_at !== null) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['username' => 'Akun ini tidak aktif.']);
        }

        // Check if user has a valid role
        if (!$user->hasValidRole()) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['username' => 'Role tidak valid.']);
        }

        // Redirect based on role with specific messages
        switch ($user->roles) {
            case 'admin':
                return redirect()->route('indexDashboard')
                    ->with('success', 'Selamat datang, Admin!');
            case 'karyawan':
                return redirect()->route('indexDashboard')
                    ->with('success', 'Selamat datang, Karyawan!');
            default:
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['username' => 'Role tidak valid.']);
        }
    }

    public function username()
    {
        return 'username';
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
