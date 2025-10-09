<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
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
