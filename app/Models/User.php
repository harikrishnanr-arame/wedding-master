<?php

namespace App\Models;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;


/**
 * User model representing a user in the application.
 *
 * This model handles user authentication and stores user information such as name, email,
 * password, and mobile number.
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_name',
        'email',
        'password',
        'mobile_number',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    /**
     * Having it as a function will break password reset. so i change it like this (aswin)
     */
   protected $casts = [
    'email_verified_at' => 'datetime',
    ];



    public function sendPasswordResetNotification($token) {

        $this->notify(new ResetPassword($token));
    }

    /** for the admin check */
    public function isAdmin()
    {
        return strtolower(trim($this->role)) === 'admin';
    }

}
   