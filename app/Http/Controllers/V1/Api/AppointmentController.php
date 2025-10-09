<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Support\Facades\DB;



class AppointmentController extends Controller
{
    //
    public function store(AppointmentRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $appointment = DB::transaction(function () use ($data, $request, $user) {


            $schedule = Schedule::where('id', $data['schedule_id'])->firstOrFail();
            if (!$schedule->is_available) {
                throw new \Exception('Schedule not available', 400);
            }
            if (!Service::where('id', $data['service_id'])->where('master_id', $data['master_id'])->exists()) {
                throw new \Exception('Service does not belong to this master', 400);
            }
            $appointment = Appointment::create([
                'status' => $data['status'],
                'notes' => $data['notes'],
                'client_id' => $user->id,
                'master_id' => $data['master_id'],
                'service_id' => $data['service_id'],
                'schedule_id' => $data['schedule_id'],
                'appointment_time' => $schedule->start_time,
            ]);

            $schedule->update(['is_available' => false]);

            return $appointment;
        });
        return response()->json([
        'id' => $appointment->id,
        'status' => $appointment->status,
        'info' => new AppointmentResource($appointment),
        'notes' => $appointment->notes,
    ], 201);

    }
}
