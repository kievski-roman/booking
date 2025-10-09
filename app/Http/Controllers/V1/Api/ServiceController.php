<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Master;
use App\Models\Service;

class ServiceController extends Controller
{
    //

    public function store(ServiceRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $master = $user->master;
        if (!$master) {
            return response()->json(['error' => 'Master profile not found'], 404);
        }

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
    public function update(ServiceRequest $request, Service $service)
    {
        $data = $request->validated();
        $user = $request->user();
        $master = $user->master;
        if (!$master) {
            return response()->json(['error' => 'Master profile not found'], 404);
        }
        if ($service->master_id !== $master->id) {
            return response()->json(['error' => 'Unauthorized to update this service'], 403);
        }
        $service->update([
            "name" => $data['name'],
            "description" => $data['description'],
            "price" => $data['price'],
        ]);
        return response()->json([
            "success" => true,
            'service' => new ServiceResource($service),
        ]);
    }
    public function destroy(Service $service)
    {

    }
}
