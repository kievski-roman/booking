<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';

    protected $fillable = [
        'client_id', 'master_id', 'service_id', 'schedule_id', 'appointment_time', 'status', 'notes'
    ];

    protected $casts = [
        'appointment_time' => 'datetime',
    ];

    // Отношения
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function master()
    {
        return $this->belongsTo(Master::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
