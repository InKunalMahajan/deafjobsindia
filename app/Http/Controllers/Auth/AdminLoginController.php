<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
class AdminLoginController extends Controller {
    public function create(): View { return view('admin.login'); }
    public function store(Request $request): RedirectResponse {
        $credentials=$request->validate(['email'=>['required','email'],'password'=>['required','string']]);
        if (! Auth::attempt($credentials)) {
            return back()->withErrors(['email'=>'These credentials do not match our records.'])->onlyInput('email');
        }
        $request->session()->regenerate();
        if (! $request->user()->can('admin.view')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['email'=>'Administrator access is not available.']);
        }
        return redirect()->intended(route('admin.dashboard'));
    }
    public function destroy(Request $request): RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
