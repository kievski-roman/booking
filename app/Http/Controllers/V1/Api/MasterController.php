<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\MasterResource;
use App\Http\Resources\ServiceResource;
use App\Models\Master;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

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
    public function show($id)
    {
        $master = Master::with('services', 'schedules')->findOrFail($id);
        return response()->json([
           new MasterResource($master),
        ]);
    }

}
