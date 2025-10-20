<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Service\AppointmentService;
use Illuminate\Http\Request;

class MasterAppointmentController extends Controller
{
    public function __construct(private AppointmentService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Appointment::class);

        $master = $request->user()->master()->firstOrFail();
        $query = Appointment::query()
            ->with(['schedule', 'service'])
            ->where('master_id', $master->id)
            ->orderByDesc('appointment_time');

        return
            AppointmentResource::collection($query->paginate(10));
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);

        $appointment->load(['schedule', 'service', 'client']);
        return new AppointmentResource($appointment);
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        $this->authorize('cancel', $appointment);

        $request->validate([
            'status' => 'prohibited',
            'notes' => 'prohibited',
        ]);

        $this->service->cancel($appointment);
        return new AppointmentResource($appointment->fresh()->load(['schedule', 'service']));
    }

    public function confirm(Request $request, Appointment $appointment)
    {
        $this->authorize('confirm', $appointment);
        $request->validate([
            'status' => 'prohibited',
            'notes' => 'prohibited',
        ]);
        $this->service->confirmed($appointment);
        return new AppointmentResource($appointment->fresh()->load(['schedule', 'service']));

    }
}
