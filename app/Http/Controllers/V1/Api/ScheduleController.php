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

        $master = $request->user()->master()->firstOrFail();

        $schedule = $master->schedules()->create($request->validated());

        return (new ScheduleResource($schedule))->response()->setStatusCode(201);
    }

    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule);

        $schedule->update($request->validated());

        return (new ScheduleResource($schedule->refresh()))->response();
    }

    public function destroy(Schedule $schedule)
    {
        $this->authorize('delete', $schedule);
        $schedule->delete();

        return response()->json(['success' => true], 204);
    }
}
