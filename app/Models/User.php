<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'status',
        'phone'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    // Отношения
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function master()
    {
        return $this->hasOne(Master::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }
}
