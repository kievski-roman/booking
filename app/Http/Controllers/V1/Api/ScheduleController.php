<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $this->authorize('viewAny', Schedule::class);
        $schedule = $user->master->schedules()->paginate(10);
        return ScheduleResource::collection($schedule);
    }

    public function show(Schedule $schedule)
    {
        $this->authorize('view', $schedule);
        return new ScheduleResource($schedule);
    }

    public function store(ScheduleRequest $request)
    {
        $this->authorize('create', Schedule::class);
        $data = $request->validated();
        $user = $request->user();
        $master = $user->master;
        if (!$master) {
            return response(['error' => 'Master id not found'], 404);
        }
        if ($user->role->slug === 'master') {
            $schedule = Schedule::create([
                'master_id' => $master->id,
                'date' => $data['date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'is_available' => true,
            ]);
        }

        return response()->json([
            'schedule' => new ScheduleResource($schedule),
        ])->setStatusCode(201);
    }

    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule);
        $data = $request->validated();

        $schedule->update([
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'is_available' => true,
        ]);

        return response()->json([
            'schedule' => new ScheduleResource($schedule),
        ]);
    }

    public function destroy(Schedule $schedule)
    {
        $this->authorize('delete', $schedule);
        $schedule->delete();

        return response()->json(['success' => true], 204);
    }
}
