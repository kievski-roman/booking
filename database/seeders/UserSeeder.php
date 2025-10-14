<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Master;
use App\Models\Service;
use App\Models\Schedule;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstWhere('slug', 'admin');
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'phone' => '0123456789',
            ]
        );
        $masterRole = Role::firstWhere('slug', 'master');
        $masters = User::factory(2)->create([
            'role_id' => $masterRole->id,
            'password' => Hash::make('password'),
        ]);
        foreach ($masters as $masterUser) {
            $master = Master::factory()->for($masterUser)->create();

            $services = Service::factory(2)->create([
                'master_id' => $master->id,
            ]);

            Schedule::factory(3)->create([
                'master_id' => $master->id,
                'is_available' => true,
            ]);
        }
        $clientRole = Role::firstWhere('slug', 'client');
        User::factory(2)->create([
            'role_id' => $clientRole->id,
            'password' => Hash::make('password'),
        ]);
    }
}
