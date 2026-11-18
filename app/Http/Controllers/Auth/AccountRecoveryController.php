<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class AccountRecoveryController extends Controller {
    private function role(string $portal): string {
        abort_unless(in_array($portal,['seeker','employer'],true),404);
        return $portal==='seeker' ? 'job_seeker' : 'employer';
    }
    public function requestForm(string $portal): View {
        $this->role($portal);
        return view('auth.forgot-password',compact('portal'));
    }
    public function sendLink(Request $request,string $portal): RedirectResponse {
        $role=$this->role($portal);
        $data=$request->validate(['email'=>['required','email']]);
        $user=User::where('email',$data['email'])->where('role',$role)->first();
        if($user) Password::sendResetLink(['email'=>$user->email]);
        return back()->with('status','If an eligible account exists, a password reset email will be sent.');
    }
    public function resetForm(Request $request,string $portal,string $token): View {
        $this->role($portal);
        return view('auth.reset-password',[
            'portal'=>$portal,'token'=>$token,'email'=>$request->query('email',''),
        ]);
    }
    public function reset(Request $request,string $portal): RedirectResponse {
        $role=$this->role($portal);
        $data=$request->validate([
            'token'=>['required','string'],
            'email'=>['required','email'],
            'password'=>['required','confirmed',PasswordRule::min(12)],
        ]);
        abort_unless(User::where('email',$data['email'])->where('role',$role)->exists(),422);
        $status=Password::reset($data,function(User $user,string $password) use($role) {
            abort_unless($user->role===$role,403);
            $user->forceFill(['password'=>Hash::make($password),'remember_token'=>Str::random(60)])->save();
            event(new PasswordReset($user));
        });
        return $status===Password::PASSWORD_RESET
            ? redirect()->route($portal.'.login')->with('status','Password reset. Please sign in.')
            : back()->withErrors(['email'=>'The reset link is invalid or expired.']);
    }
}
