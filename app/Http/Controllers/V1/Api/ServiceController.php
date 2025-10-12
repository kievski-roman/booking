<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    //

    public function index(Request $request)
    {
        $user = $request->user();

        $this->authorize('viewAny', Service::class);

        $services = $user->master->services()->paginate(10);
        return ServiceResource::collection($services);
    }
    public function show(Service $service)
    {
        $this->authorize('view', $service);
        return new ServiceResource($service);
    }
    public function store(ServiceRequest $request)
    {
        $this->authorize('create', Service::class);
        $data = $request->validated();
        $user = $request->user();
        $master = $user->master;
        if (! $master) {
            return response()->json(['error' => 'Master profile not found'], 404);
        }

        $service = Service::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'master_id' => $master->id,
        ]);

        return response()->json([
            'success' => true,
            'service' => new ServiceResource($service),
        ]);
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $this->authorize('update', $service);
        $data = $request->validated();
        $user = $request->user();
        $master = $user->master;
        if (! $master) {
            return response()->json(['error' => 'Master profile not found'], 404);
        }
        if ($service->master_id !== $master->id) {
            return response()->json(['error' => 'Unauthorized to update this service'], 403);
        }
        $service->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
        ]);

        return response()->json([
            'success' => true,
            'service' => new ServiceResource($service),
        ]);
    }

    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);
        $service->delete();

        return response()->json(['success' => true], 204);
    }
}
