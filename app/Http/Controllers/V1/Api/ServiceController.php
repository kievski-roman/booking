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

        $master = $request->user()->master()->firstOrFail();

        $service = $master->services()->create($request->validated());

        return (new ServiceResource($service))->response()->setStatusCode(201);
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $this->authorize('update', $service);

        $service->update($request->validated());

        return (new ServiceResource($service->refresh()))->response();
    }

    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);
        $service->delete();

        return response()->json(['success' => true], 204);
    }
}
