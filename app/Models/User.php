<?php
namespace App\Models;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\URL;
class User extends Authenticatable implements MustVerifyEmail {
    use HasFactory,Notifiable;
    protected $fillable=['name','email','password','bio','location','headline','education','experience_years','skills','preferred_location','preferred_work_mode','requires_captions','prefers_isl'];
    protected $hidden=['password','remember_token'];
    protected function casts(): array { return ['email_verified_at'=>'datetime','password'=>'hashed','requires_captions'=>'boolean','prefers_isl'=>'boolean']; }
    public function sendPasswordResetNotification($token): void {
        $portal=$this->role==='employer' ? 'employer' : 'seeker';
        $this->notify((new ResetPassword($token))->createUrlUsing(function($notifiable,$token) use($portal) {
            return route($portal.'.password.reset',['token'=>$token,'email'=>$notifiable->getEmailForPasswordReset()]);
        }));
    }
    public function isSuperAdmin(): bool { return $this->role==='super_admin'; }
}
