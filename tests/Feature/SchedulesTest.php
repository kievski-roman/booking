<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{User, Master, Schedule};

class SchedulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_creates_schedule_slot(): void
    {
        $m = User::factory()->asMaster()->create();
        Master::factory()->for($m)->create();

        $payload = [
            'date' => now()->addDays(2)->toDateString(),
            'start_time' => '14:00',
            'end_time' => '15:00',
        ];

        $this->withToken($m->createToken('api')->plainTextToken)
            ->postJson('/api/v1/schedules', $payload)
            ->assertCreated()
            ->assertJsonPath('data.master_id', $m->master->id);

        $this->assertDatabaseHas('schedules', [
            'master_id' => $m->master->id,
            'date' => $payload['date'],
        ]);
    }

    public function test_client_cannot_create_schedule(): void
    {
        $c = User::factory()->asClient()->create();

        $this->withToken($c->createToken('api')->plainTextToken)
            ->postJson('/api/v1/schedules', [
                'date' => now()->addDay()->toDateString(),
                'start_time' => '10:00',
                'end_time' => '11:00',
            ])->assertStatus(403);
    }
}
