<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MasterResource;
use App\Http\Resources\ServiceResource;
use App\Models\Master;

class MasterController extends Controller
{
    //

    public function index()
    {
        $this->authorize('viewAny', Master::class);
        $masters = Master::with('services')->paginate(10);

        return MasterResource::collection($masters);
    }

    public function show(Master $master)
    {
        $this->authorize('view', $master);
        $master->load('services','schedules');
        return
            new MasterResource($master);
    }
}
