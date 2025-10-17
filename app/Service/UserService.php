<?php

namespace App\Service;

use App\Models\Master;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
        public function registerClient(array $data): User
        {
            $roleId = Role::where('slug', 'client')->value('id');

            return User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'password'   => Hash::make($data['password']),
                'phone'      => $data['phone'] ?? null,
                'role_id'    => $roleId,
                'status'     => 'active',
            ]);
        }
        public function registerMaster(array $data): User
        {
            try{
                DB::beginTransaction();
                $roleId = Role::where('slug', 'master')->value('id');

                $user = User::create([
                    'first_name' => $data['first_name'],
                    'last_name'  => $data['last_name'],
                    'email'      => $data['email'],
                    'password'   => Hash::make($data['password']),
                    'phone'      => $data['phone'] ?? null,
                    'role_id'    => $roleId,
                    'status'     => 'active',
                ]);
                Master::create([
                    'location' => $data['location'],
                    'bio' => $data['bio'],
                    'user_id' => $user->id,
                ]);
                DB::commit();
                return $user->load('master');

            }catch (\Exception $exception){
                DB::rollBack();
                abort(500, $exception->getMessage());
            }
        }
        public function update()
        {

        }

}
