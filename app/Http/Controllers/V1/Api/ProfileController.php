<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\MasterResource;
use App\Http\Resources\UserResource;
use App\Models\Master;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    //
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $user = User::paginate(10);
        return response()->json(
            userResource::collection($user),
        );
    }

    public function show(Request $request)
    {
        $user = $request->user();
        $master = $user->master;
        if ($master) {
            return response()->json([
                'user ' => UserResource::make($user),
                'master' => MasterResource::make($master),
            ]);
        }
        return new UserResource($user);
    }

    public function update(UserRequest $request)
    {
        $user = $request->user();
        $this->authorize('update', $user);
        $data = $request->validated();

        $response = DB::transaction(function () use ($user, $data) {
            $user->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => isset($data['password']) ? Hash::make($data['password']) : $user->password,
            ]);

            if ($user->role->slug === 'master') {
                $masterData = array_intersect_key($data, array_flip(['location', 'bio']));
                if ($masterData) {
                    $user->master()->updateOrCreate(
                        ['user_id' => $user->id],
                        $masterData
                    );
                }
            }

            return [
                'user' => UserResource::make($user->fresh()),
                'master' => $user->master ? MasterResource::make($user->master->fresh()) : null,
            ];
        });
        return response()->json($response, 200);

    }


}
