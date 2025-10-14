<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Schedule extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'master_id', 'date', 'start_time', 'end_time', 'is_available',
    ];

    protected $casts = [
        'is_available' => 'bool',
        'date' => 'date:Y-m-d',
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
