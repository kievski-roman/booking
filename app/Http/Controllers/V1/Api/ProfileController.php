<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\MasterResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = User::query()->paginate(10);
        return UserResource::collection($users);
    }

    public function show(Request $request)
    {
        $user = $request->user();
        $this->authorize('view', $user);

        $master = $user->master;
        return response()->json([
            'user' => new UserResource($user),
            'master' => $master ? new MasterResource($master) : null,
        ]);
    }

    public function updateUser(UpdateUserRequest $request)
    {
        $user = $request->user();
        $this->authorize('update', $user);

        $data = $request->validated();

        DB::transaction(function () use ($user, $data) {
            $payload = [
                'first_name' => $data['first_name'] ?? $user->first_name,
                'last_name' => $data['last_name'] ?? $user->last_name,
                'email' => $data['email'] ?? $user->email,
                'phone' => array_key_exists('phone', $data) ? $data['phone'] : $user->phone,
            ];

            if (!empty($data['password'])) {
                $payload['password'] = Hash::make($data['password']);
            }

            $user->update($payload);
        });

        return response()->json([
            'user' => new UserResource($user->fresh()),
            'master' => $user->master ? new MasterResource($user->master->fresh()) : null,
        ]);
    }

    public function updateMaster(Request $request)
    {

        $user = $request->user();
        $this->authorize('update', $user);

        $data = $request->validate([
            'bio' => 'required|string|min:10|max:1000',
            'location' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($user, $data) {
            $user->master()->updateOrCreate(
                ['user_id' => $user->id],
                ['bio' => $data['bio'], 'location' => $data['location']]
            );
        });

        return response()->json([
            'user' => new UserResource($user->fresh()),
            'master' => new MasterResource($user->master->fresh()),
        ]);
    }
}
