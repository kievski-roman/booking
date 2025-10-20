<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\Service;
use App\Service\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientAppointmentController extends Controller
{
    public function __construct(private AppointmentService $service) {}
    public function index(Request $request)
    {
        $this->authorize('viewAny', Appointment::class);

        $query = Appointment::query()
            ->with(['schedule', 'service'])
            ->where('client_id', $request->user()->id)
            ->orderByDesc('appointment_time');

        return
            AppointmentResource::collection($query->paginate(10));
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);

        $appointment->load(['schedule', 'service', 'master']);
        return new AppointmentResource($appointment);
    }

    public function store(AppointmentRequest $request)
    {
        $this->authorize('create', Appointment::class);

        $appointment = $this->service->book($request->user(), $request->validated());

        return (new AppointmentResource($appointment))
            ->response()
            ->setStatusCode(201);
    }
    public function cancel(Request $request, Appointment $appointment)
    {
        $this->authorize('cancel', $appointment);

        $request->validate([
            'status' => 'prohibited',
            'notes'  => 'prohibited',
        ]);

        $tooLate = now()->gt($appointment->appointment_time->copy()->subHours(24));
        if ($tooLate) {
            return response()->json([
                'error' => 'Отмена возможна только за 24 часа до начала записи',
            ], 403);
        }
        $this->service->cancel($appointment);

        return new AppointmentResource($appointment->fresh()->load(['schedule','service','master']));
    }
}
