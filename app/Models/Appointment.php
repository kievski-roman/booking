<?php

namespace App\Models;

use App\Policies\AppointmentPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Appointment extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'appointments';

    protected $fillable = [
        'client_id', 'master_id', 'service_id', 'schedule_id', 'appointment_time', 'status', 'notes',
    ];

    protected $casts = [
        'appointment_time' => 'datetime',
    ];

    // Отношения
    public function client()
    {
        return $this->belongsTo(User::class, 'role_id');
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
