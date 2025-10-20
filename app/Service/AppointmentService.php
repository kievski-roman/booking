<?php

namespace App\Service;

use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    public function tooLate(Appointment $appointment, int $time): void
    {
        $tooLate = now()->gt($appointment->appointment_time->copy()->subHours($time));
        if ($tooLate) {
            abort(409, 'Too late');
        }
    }

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
            $startsAt = Carbon::parse($schedule->date.' '.$schedule->start_time);
            return Appointment::create([
                'client_id' => $user->id,
                'master_id' => $masterId,
                'service_id' => $service->id,
                'schedule_id' => $schedule->id,
                'appointment_time' => $startsAt,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }

    public function cancel(Appointment $appointment): Appointment
    {
        $this->tooLate($appointment, 24);
        return DB::transaction(function () use ($appointment) {
            $locked = Appointment::whereKey($appointment->id)
                ->lockForUpdate()
                ->firstOrFail();
            if ($locked->status === 'cancelled') {
                abort(409, 'Appointment already cancelled');
            }
            if (in_array($locked->status, ['pending', 'confirmed'], true)) {
                $scheduleId = $appointment->schedule_id;

                Schedule::whereKey($scheduleId)->update(['is_available' => true]);
            }

            $locked->update(['status' => 'cancelled']);
            return $locked->fresh();
        });
    }

    public function confirmed(Appointment $appointment): Appointment
    {
        $this->tooLate($appointment, 6);
        return DB::transaction(function () use ($appointment) {
            $locked = Appointment::whereKey($appointment->id)
                ->lockForUpdate()
                ->firstOrFail();
            if (in_array($locked->status, ['cancelled', 'confirmed'], true)) {
                abort(409, 'Your cant change appointment status');
            } elseif ($locked->status === 'pending') {
                $locked->update(['status' => 'confirmed']);
            }
            return $locked->fresh();
        });
    }

}
