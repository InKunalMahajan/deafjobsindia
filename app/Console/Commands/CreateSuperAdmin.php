<?php
namespace App\Console\Commands;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
class CreateSuperAdmin extends Command {
    protected $signature='admin:create';
    protected $description='Interactively create the first Super Admin';
    public function handle(): int {
        $name=$this->ask('Name');
        $email=$this->ask('Email');
        $password=$this->secret('Password (12+ characters)');
        if (! $name || ! filter_var($email,FILTER_VALIDATE_EMAIL) || ! $password || mb_strlen($password)<12) {
            $this->error('Valid name, email and password of at least 12 characters required.');
            return self::FAILURE;
        }
        if (User::where('email',$email)->exists()) {
            $this->error('Account already exists. Refusing to promote an existing account.');
            return self::FAILURE;
        }
        $user=User::create(['name'=>$name,'email'=>$email,'password'=>Hash::make($password)]);
        $user->role='super_admin';$user->save();
        $this->info('Super Admin created. Protect these credentials.');
        return self::SUCCESS;
    }
}
