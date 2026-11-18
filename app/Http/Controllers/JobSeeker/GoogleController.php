<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller {
    public function redirect(): RedirectResponse {
        abort_unless(config('services.google.client_id') && config('services.google.client_secret'),503);
        return Socialite::driver('google')->redirect();
    }
    public function callback(Request $request): RedirectResponse {
        $google=Socialite::driver('google')->user();
        $email=$google->getEmail();
        abort_unless($email && filter_var($email,FILTER_VALIDATE_EMAIL),403);
        // Never silently link Google to an existing password/admin account.
        $user=User::where('google_id',$google->getId())->first();
        if (! $user) {
            abort_if(User::where('email',$email)->exists(),409,'An account with this email already exists. Sign in with your password.');
            $user=User::create([
                'name'=>$google->getName() ?: $email,
                'email'=>$email,
                'password'=>Hash::make(Str::random(64)),
            ]);
            $user->role='job_seeker';
            $user->google_id=$google->getId();
            $user->save();
        }
        abort_unless($user->role==='job_seeker',403);
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('seeker.dashboard');
    }
}
