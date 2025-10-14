<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Role extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = 'roles';

    protected $fillable = [
        'slug', 'name', 'permissions', 'is_active',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];

    // Отношения
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
