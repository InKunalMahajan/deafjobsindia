<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller {
    public function registerForm(): View { return view('job-seeker.register'); }
    public function loginForm(): View { return view('job-seeker.login'); }

    public function register(Request $request): RedirectResponse {
        $data=$request->validate([
            'name'=>['required','string','max:255'],
            'email'=>['required','email','max:255','unique:users,email'],
            'password'=>['required','confirmed',Password::min(12)],
        ]);
        $user=User::create($data);
        $user->role='job_seeker';
        $user->save();
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('seeker.dashboard');
    }

    public function login(Request $request): RedirectResponse {
        $credentials=$request->validate([
            'email'=>['required','email'],
            'password'=>['required','string'],
        ]);
        if (! Auth::attempt([...$credentials,'role'=>'job_seeker'])) {
            return back()->withErrors(['email'=>'Invalid job-seeker credentials.'])->onlyInput('email');
        }
        $request->session()->regenerate();
        return redirect()->intended(route('seeker.dashboard'));
    }

    public function logout(Request $request): RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('seeker.login');
    }
}
