<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'slug', 'name', 'permissions', 'is_active'
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
