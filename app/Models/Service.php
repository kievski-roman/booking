<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Service extends Model
{
    use HasApiTokens;
    protected $table = 'services';

    protected $fillable = [
        'name', 'description', 'price', 'master_id',
    ];

    // Отношения
    public function master()
    {
        return $this->belongsTo(Master::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
