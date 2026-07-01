<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('loginpage.login'); // adjust to your actual blade path
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'account'  => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($credentials['account'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        if (! Auth::attempt([$loginField => $credentials['account'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            return back()
                ->withErrors(['account' => 'Invalid username/email or password.'])
                ->onlyInput('account');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->isResident() && ! $user->resident?->verified_at) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['account' => 'Your registration is pending admin approval. Please wait until an admin confirms your account.'])
                ->onlyInput('account');
        }

        if ($user->status !== 'active') {
            Auth::logout();
            return back()->withErrors(['account' => 'This account has been deactivated.']);
        }

        \App\Models\ActivityLog::log('login', 'Auth', "User logged into the system.");

        return $user->isResident()
            ? redirect()->intended('/home')
            : redirect()->intended('/admin/dashboard');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            \App\Models\ActivityLog::log('logout', 'Auth', "User logged out.");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
