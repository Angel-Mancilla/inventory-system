<?php

namespace Tests\Feature\Conditions;

use App\Models\Condition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConditionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_usuario_autenticado_puede_crear_una_condicion(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/conditions', [
            'name' => 'Nuevo',
            'warranty_days' => 365,
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('conditions', ['name' => 'Nuevo', 'warranty_days' => 365]);
    }

    public function test_puede_actualizar_una_condicion(): void
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create(['warranty_days' => 30]);

        $response = $this->actingAs($user)->put("/conditions/{$condition->id}", [
            'name' => $condition->name,
            'warranty_days' => 90,
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('conditions', ['id' => $condition->id, 'warranty_days' => 90]);
    }

    public function test_puede_eliminar_una_condicion(): void
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();

        $response = $this->actingAs($user)->delete("/conditions/{$condition->id}");

        $response->assertRedirect();
        // Sin SoftDeletes en esta tabla: el registro debe desaparecer por completo
        $this->assertDatabaseMissing('conditions', ['id' => $condition->id]);
    }

    public function test_un_invitado_no_puede_crear_condiciones(): void
    {
        $response = $this->post('/conditions', ['name' => 'Nuevo', 'warranty_days' => 365]);

        $response->assertRedirect('/login');
    }

    public function test_warranty_days_es_obligatorio_y_numerico(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/conditions', [
            'name' => 'Nuevo',
            'warranty_days' => 'no-es-numero',
            'is_active' => true,
        ]);

        $response->assertSessionHasErrors('warranty_days');
    }
}