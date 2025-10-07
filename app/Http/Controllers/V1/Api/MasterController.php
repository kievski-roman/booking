<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Master;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    //

    public function index(Request $request)
    {
        $test = "fff";
        return response()->json([
            "okey" => $request->$test,
        ]);
    }
    public function store(ServiceRequest $request, User $user)
    {
        $data = $request->validated();
        $user = $request->user();
        $master = Master::where('user_id', $user->id)->firstOrFail();

        $service = Service::create([
            "name" => $data['name'],
            "description" => $data['description'],
            "price" => $data['price'],
            "master_id" => $master->id
        ]);
        return response()->json([
            "success" => true,
            'service' => new ServiceResource($service),
        ]);

    }
}
