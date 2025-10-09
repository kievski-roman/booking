<?php

namespace App\Models;

use App\Policies\MasterPolicy;
use App\Policies\UserPolicy;
use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
    protected $table = 'masters';

    protected $fillable = [
        'user_id', 'bio', 'location', 'rating',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
    ];

    // Отношения
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
