<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Appointment::class);
        $appointments = Appointment::with('schedule')->paginate(10);
        return
            AppointmentResource::collection($appointments);
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        $appointment->load('master', 'service', 'schedule');
        return new AppointmentResource($appointment);
    }

    public function store(AppointmentRequest $request)
    {
        $this->authorize('create', Appointment::class);
        $data = $request->validated();
        $user = $request->user();
        $appointment = DB::transaction(function () use ($data, $user) {

            $service = Service::query()->findOrFail($data['service_id']);
            if ($service->master_id !== $data['master_id']) {
                abort(422, 'Service does not belong to this master');
            }

            $schedule = Schedule::query()
                ->whereKey($data['schedule_id'])
                ->where('master_id', $data['master_id'])
                ->lockForUpdate()
                ->first();
            if (!$schedule) {
                abort(422, 'Schedule not found for this master');
            }
            if (!$schedule->is_available) {
                abort(409, 'Schedule not available');
            }
            $appointment = Appointment::create([
                'notes' => $data['notes'] ?? null,
                'client_id' => $user->id,
                'master_id' => $data['master_id'],
                'service_id' => $data['service_id'],
                'schedule_id' => $data['schedule_id'],
                'appointment_time' => $schedule->start_time,
                'status' => 'pending',
            ]);

            $schedule->update(['is_available' => false]);

            return $appointment;
        });
        return new AppointmentResource($appointment);

    }

    public function update(Request $request, Appointment $appointment)
    {
        $user = $request->user();
        $this->authorize('update', $appointment);
        $allowedStatuses = $user->role->slug === 'master' ? ['cancelled', 'confirmed'] : ['cancelled'];
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', $allowedStatuses)],
        ]);


        if ($data['status'] === 'cancelled') {
            $hoursBefore = $user->role->slug === 'master' ? 10 : 24;
            if (Carbon::now()->diffInHours($appointment->appointment_time, false) < $hoursBefore) {
                $errorMsg = $user->role->slug === 'master'
                    ? 'Отмена возможна только за 10 часов до начала записи'
                    : 'Отмена возможна только за 24 часа до начала записи';
                return response()->json(['error' => $errorMsg], 403);
            }
        }

        if ($data['status'] === 'confirmed' && Carbon::now()->diffInHours($appointment->appointment_time, false) < 1) {
            return response()->json(['error' => 'Подтверждение невозможно за час до начала записи'], 403);
        }
        $appointment->update([
            'status' => $data['status'],
        ]);
        return new AppointmentResource($appointment);

    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);

    }
}
