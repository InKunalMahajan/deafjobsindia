<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
class UserController extends Controller {
    public function index(): View { return view('admin.users.index',['users'=>User::orderBy('name')->paginate(20)]); }
    public function create(): View { return view('admin.users.form',['user'=>new User()]); }
    public function store(Request $request): RedirectResponse {
        $data=$request->validate(['name'=>['required','string','max:255'],
            'email'=>['required','email','max:255','unique:users,email'],
            'password'=>['required','string','min:12','confirmed'],
            'role'=>['required',Rule::in(['super_admin','admin','editor','viewer'])]]);
        $user=User::create(collect($data)->except('role')->all());
        $user->role=$data['role'];$user->save();
        return redirect()->route('admin.users.index')->with('status','User created.');
    }
    public function edit(User $user): View { return view('admin.users.form',compact('user')); }
    public function update(Request $request,User $user): RedirectResponse {
        $data=$request->validate(['name'=>['required','string','max:255'],
            'email'=>['required','email','max:255',Rule::unique('users','email')->ignore($user->id)],
            'password'=>['nullable','string','min:12','confirmed'],
            'role'=>['required',Rule::in(['super_admin','admin','editor','viewer'])]]);
        if ($user->is($request->user()) && $data['role']!=='super_admin') {
            return back()->withErrors(['role'=>'You cannot remove your own Super Admin role.']);
        }
        $user->name=$data['name'];$user->email=$data['email'];$user->role=$data['role'];
        if (! empty($data['password'])) $user->password=$data['password'];
        $user->save();
        return redirect()->route('admin.users.index')->with('status','User updated.');
    }
    public function destroy(Request $request,User $user): RedirectResponse {
        if ($user->is($request->user())) return back()->withErrors(['user'=>'You cannot delete your own account.']);
        $user->delete();
        return back()->with('status','User deleted.');
    }
}
