<?php

namespace App\Service;

use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    public function book(User $user, array $data): Appointment
    {
        return DB::transaction(function () use ($user, $data) {
            $service = Service::findOrFail($data['service_id']);
            $masterId = $service->master_id;


            $schedule = Schedule::whereKey($data['schedule_id'])
                ->where('master_id', $masterId)
                ->lockForUpdate()
                ->firstOrFail();
            if (!$schedule->is_available) {
                abort(409, 'Schedule not available');
            }

            $updated = Schedule::whereKey($schedule->id)
                ->where('is_available', true)
                ->update(['is_available' => false]);

            if (!$updated) {
                abort(409, 'Schedule just became unavailable');
            }

            return Appointment::create([
                'client_id' => $user->id,
                'master_id' => $masterId,
                'service_id' => $service->id,
                'schedule_id' => $schedule->id,
                'appointment_time' => $schedule->start_time,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }

//    public function cancel(User $user, Appointment $appointment): Appointment
//    {
//
//    }
}
