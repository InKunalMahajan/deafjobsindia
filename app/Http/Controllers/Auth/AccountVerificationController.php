<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountVerificationController extends Controller {
    public function notice(Request $request): View {
        return view('auth.verify-email');
    }
    public function verify(Request $request,int $id,string $hash): RedirectResponse {
        $user=$request->user();
        abort_unless($user && $user->id===$id,403);
        abort_unless(hash_equals(sha1($user->getEmailForVerification()),$hash),403);
        abort_unless(in_array($user->role,['job_seeker','employer'],true),403);
        if(!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }
        return redirect()->route($user->role==='employer'?'employer.dashboard':'seeker.dashboard')
            ->with('status','Email verified.');
    }
    public function resend(Request $request): RedirectResponse {
        $user=$request->user();
        abort_unless(in_array($user->role,['job_seeker','employer'],true),403);
        if(!$user->hasVerifiedEmail()) $user->sendEmailVerificationNotification();
        return back()->with('status','If your email is not verified, a verification link has been sent.');
    }
}
