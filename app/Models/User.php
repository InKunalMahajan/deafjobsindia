<?php
namespace App\Models;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
class User extends Authenticatable implements MustVerifyEmail {
    use HasFactory,Notifiable;
    protected $fillable=['name','email','password','bio','location','headline','education','experience_years','skills','preferred_location','preferred_work_mode','requires_captions','prefers_isl'];
    protected $hidden=['password','remember_token'];
    protected function casts(): array { return ['email_verified_at'=>'datetime','password'=>'hashed','requires_captions'=>'boolean','prefers_isl'=>'boolean']; }
    public function isSuperAdmin(): bool { return $this->role==='super_admin'; }
}
