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
    public $service;

    public function __construct(AppointmentService $service)
    {
        $this->service = $service;
    }

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
    public function cancel(Appointment $appointment, Request $request)
    {
        $this->authorize('cancel', $appointment);
        $request->validate(['status' => 'prohibited']);
        $appointment->update([
            'status' => 'cancelled'
        ]);
        return new AppointmentResource($appointment
            ->with(['schedule', 'service', 'master']));
    }
}
