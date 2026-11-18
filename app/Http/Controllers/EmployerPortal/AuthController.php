<?php

namespace App\Http\Controllers\EmployerPortal;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller {
    public function registerForm(): View { return view('employer-portal.register'); }
    public function loginForm(): View { return view('employer-portal.login'); }

    public function register(Request $request): RedirectResponse {
        $data=$request->validate([
            'name'=>['required','string','max:255'],
            'email'=>['required','email','max:255','unique:users,email'],
            'password'=>['required','confirmed',Password::min(12)],
            'company_name'=>['required','string','max:255'],
            'company_website'=>['nullable','url','starts_with:https://','max:2048'],
            'company_location'=>['required','string','max:255'],
        ]);
        $user=DB::transaction(function() use($data) {
            $user=User::create(['name'=>$data['name'],'email'=>$data['email'],'password'=>$data['password']]);
            $user->role='employer';
            $user->save();
            $employer=Employer::create([
                'name'=>$data['company_name'],
                'slug'=>Str::slug($data['company_name']).'-'.Str::lower(Str::random(10)),
                'website'=>$data['company_website'] ?? null,
                'location'=>$data['company_location'],
                'contact_email'=>$data['email'],
                'verified'=>false,
            ]);
            DB::table('employer_memberships')->insert([
                'user_id'=>$user->id,'employer_id'=>$employer->id,
                'role'=>'owner','created_at'=>now(),'updated_at'=>now(),
            ]);
            return $user;
        });
        $user->sendEmailVerificationNotification();
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('employer.dashboard');
    }

    public function login(Request $request): RedirectResponse {
        $credentials=$request->validate(['email'=>['required','email'],'password'=>['required','string']]);
        if(!Auth::attempt([...$credentials,'role'=>'employer'])) {
            return back()->withErrors(['email'=>'Invalid employer credentials.'])->onlyInput('email');
        }
        $request->session()->regenerate();
        return redirect()->intended(route('employer.dashboard'));
    }

    public function logout(Request $request): RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('employer.login');
    }
}
