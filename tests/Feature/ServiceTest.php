<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{User, Master, Service};

class ServicesCrudTest extends TestCase
{
    use RefreshDatabase;

    private function makeMasterUser(): User
    {
        $u = User::factory()->asMaster()->create();
        Master::factory()->for($u)->create();
        return $u;
    }

    private function makeClientUser(): User
    {
        return User::factory()->asClient()->create();
    }

    /** ---------- INDEX ---------- */

    public function test_index_returns_only_own_services_for_master(): void
    {
        $m1 = $this->makeMasterUser();
        $m2 = $this->makeMasterUser();

        Service::factory()->count(2)->create(['master_id' => $m1->master->id, 'name' => 'm1-x']);
        Service::factory()->count(3)->create(['master_id' => $m2->master->id, 'name' => 'm2-x']);

        $res = $this->withToken($m1->createToken('api')->plainTextToken)
            ->getJson('/api/v1/services');

        $res->assertOk()
            ->assertJsonStructure(['data','links','meta'])
            ->assertJsonCount(2, 'data');

        $names = collect($res->json('data'))->pluck('name');
        $this->assertTrue($names->every(fn($n) => str_starts_with($n, 'm1-')));
    }

    public function test_index_for_client_is_forbidden(): void
    {
        $client = $this->makeClientUser();

        $this->withToken($client->createToken('api')->plainTextToken)
            ->getJson('/api/v1/services')
            ->assertStatus(403);
    }

    /** ---------- SHOW ---------- */

    public function test_show_returns_own_service_for_master(): void
    {
        $m = $this->makeMasterUser();
        $s = Service::factory()->create(['master_id' => $m->master->id, 'name' => 'own']);

        $this->withToken($m->createToken('api')->plainTextToken)
            ->getJson("/api/v1/services/{$s->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $s->id)
            ->assertJsonPath('data.name', 'own');
    }

    public function test_show_foreign_service_is_forbidden(): void
    {
        $m1 = $this->makeMasterUser();
        $m2 = $this->makeMasterUser();
        $s2 = Service::factory()->create(['master_id' => $m2->master->id]);

        $this->withToken($m1->createToken('api')->plainTextToken)
            ->getJson("/api/v1/services/{$s2->id}")
            ->assertStatus(403);
    }

    /** ---------- STORE ---------- */

    public function test_store_validates_required_fields(): void
    {
        $m = $this->makeMasterUser();

        $this->withToken($m->createToken('api')->plainTextToken)
            ->postJson('/api/v1/services', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name','description','price']);
    }

    public function test_store_rejects_master_id_in_payload(): void
    {
        $m = $this->makeMasterUser();

        $this->withToken($m->createToken('api')->plainTextToken)
            ->postJson('/api/v1/services', [
                'name' => 'Cut',
                'description' => 'desc',
                'price' => 10,
                'master_id' => 999, // должен быть prohibited в Request
            ])->assertStatus(422)
            ->assertJsonValidationErrors(['master_id']);
    }

    public function test_master_creates_service_successfully(): void
    {
        $m = $this->makeMasterUser();

        $res = $this->withToken($m->createToken('api')->plainTextToken)
            ->postJson('/api/v1/services', [
                'name' => 'Haircut',
                'description' => 'Simple cut',
                'price' => 30,
            ]);

        $res->assertCreated()
            ->assertJsonStructure(['data'=>['id','name','price']]);

        $this->assertDatabaseHas('services', [
            'name' => 'Haircut',
            'master_id' => $m->master->id,
        ]);
    }

    public function test_client_cannot_create_service(): void
    {
        $client = $this->makeClientUser();

        $this->withToken($client->createToken('api')->plainTextToken)
            ->postJson('/api/v1/services', [
                'name' => 'X', 'description' => 'd', 'price' => 10
            ])->assertStatus(403);
    }

    /** ---------- UPDATE ---------- */

    public function test_update_own_service_success(): void
    {
        $m = $this->makeMasterUser();
        $s = Service::factory()->create(['master_id' => $m->master->id, 'name' => 'Old', 'price' => 10]);

        $this->withToken($m->createToken('api')->plainTextToken)
            ->putJson("/api/v1/services/{$s->id}", [
                'name' => 'New',
                'description' => 'updated',
                'price' => 12,
            ])
            ->assertOk()
            ->assertJsonPath('data.name', 'New');

        $this->assertDatabaseHas('services', [
            'id' => $s->id,
            'name' => 'New',
            'price' => 12,
        ]);
    }

    public function test_update_foreign_service_forbidden(): void
    {
        $m1 = $this->makeMasterUser();
        $m2 = $this->makeMasterUser();
        $s2 = Service::factory()->create(['master_id' => $m2->master->id, 'name' => 'B']);

        $this->withToken($m1->createToken('api')->plainTextToken)
            ->putJson("/api/v1/services/{$s2->id}", [
                'name' => 'Hack', 'description' => 'x', 'price' => 99
            ])->assertStatus(403);
    }

    public function test_update_validates_fields(): void
    {
        $m = $this->makeMasterUser();
        $s = Service::factory()->create(['master_id' => $m->master->id]);

        $this->withToken($m->createToken('api')->plainTextToken)
            ->putJson("/api/v1/services/{$s->id}", [
                'name' => '',
                'description' => '',
                'price' => -5,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name','description','price']);
    }

    /** ---------- DESTROY ---------- */

    public function test_destroy_own_service_returns_204(): void
    {
        $m = $this->makeMasterUser();
        $s = Service::factory()->create(['master_id' => $m->master->id]);

        $this->withToken($m->createToken('api')->plainTextToken)
            ->deleteJson("/api/v1/services/{$s->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('services', ['id' => $s->id]);
    }

    public function test_destroy_foreign_service_forbidden(): void
    {
        $m1 = $this->makeMasterUser();
        $m2 = $this->makeMasterUser();
        $s2 = Service::factory()->create(['master_id' => $m2->master->id]);

        $this->withToken($m1->createToken('api')->plainTextToken)
            ->deleteJson("/api/v1/services/{$s2->id}")
            ->assertStatus(403);

        $this->assertDatabaseHas('services', ['id' => $s2->id]);
    }
}
