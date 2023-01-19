<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'phone_number',
        'email',
        'password',
        'date_of_birth',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    /****************Start Relational functions************ */
    public function linkedSocialAccounts()
    {
        return $this->hasMany(LinkedSocialAccount::class);
    }
    

    /****************start Scopes functions************ */
    public function scopeEmail($query, $email)
    {
        return $this->where("email", $email);
    }


    public function scopeUsername($query, $username)
    {
        return $this->where("username", $username);
    }
    
    /****************End Scopes functions************ */

    /***********************Start Model Mutators****************** */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
    /***********************#End Model Mutators****************** */
}
