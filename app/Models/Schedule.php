<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';

    protected $fillable = [
        'master_id', 'date', 'start_time', 'end_time', 'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
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
