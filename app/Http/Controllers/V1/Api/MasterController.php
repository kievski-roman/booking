<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MasterResource;
use App\Models\Master;

class MasterController extends Controller
{
    //

    public function index()
    {
        $masters = Master::with('services')->paginate(10);

        return response()->json([
            'masters' => MasterResource::collection($masters),
        ]);
    }

    public function show(Master $master)
    {
        return response()->json([
            new MasterResource($master),
        ]);
    }
}
