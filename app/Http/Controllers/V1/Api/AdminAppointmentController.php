<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AdminAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Appointment::class);
        $query = Appointment::query()->with(['schedule', 'service','master']);
        return AppointmentResource::collection($query->paginate(10));
    }
    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);

    }
}
