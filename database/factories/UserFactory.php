<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role_id' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function asAdmin(): static
    {
        return $this->state(function () {
            $role = Role::firstOrCreate(
                ['slug' => 'admin'],
                ['name' => 'Administrator']
            );
            return ['role_id' => $role->id];
        });
    }
    public function asMaster(): static
    {
        return $this->state(function () {
            $role = Role::firstOrCreate(
                ['slug' => 'master'],
                ['name' => 'Master']
            );
            return ['role_id' => $role->id];
        });
    }

    public function asClient(): static
    {
        return $this->state(function () {
            $role = Role::firstOrCreate(
                ['slug' => 'client'],
                ['name' => 'Client']
            );
            return ['role_id' => $role->id];
        });
    }


    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
