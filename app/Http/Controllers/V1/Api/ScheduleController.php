<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\Master;
use App\Models\Role;
use App\Models\Schedule;

class ScheduleController extends Controller
{

    public function store(ScheduleRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $master = $user->master;
        if(!$master){
            return response(['error' =>'Master id not found'], 404);
        }
        if($user->role->slug === 'master'){
            $schedule = Schedule::create([
                'master_id' => $master->id,
                'date' => $data['date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'is_available' => true,
            ]);
        }
        return response()->json([
            "schedule" => new ScheduleResource($schedule),
        ]);
    }
    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        $data = $request->validated();

        $schedule->update([
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'is_available' => true,
        ]);
        return response()->json([
            "schedule" => new ScheduleResource($schedule),
        ]);
    }
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return response()->json(['success' => true],204);
    }
}
