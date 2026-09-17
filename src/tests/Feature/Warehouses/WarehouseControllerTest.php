<?php

namespace Tests\Feature\Warehouses;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_usuario_autenticado_puede_crear_un_almacen(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/warehouses', [
            'name' => 'Sucursal Centro',
            'location' => 'Av. Central 123',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('warehouses', ['name' => 'Sucursal Centro']);
    }

    public function test_location_es_opcional(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/warehouses', [
            'name' => 'Bodega Norte',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('warehouses', ['name' => 'Bodega Norte', 'location' => null]);
    }

    public function test_puede_actualizar_un_almacen(): void
    {
        $user = User::factory()->create();
        $warehouse = Warehouse::factory()->create(['name' => 'Viejo nombre']);

        $response = $this->actingAs($user)->put("/warehouses/{$warehouse->id}", [
            'name' => 'Nuevo nombre',
            'location' => $warehouse->location,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('warehouses', ['id' => $warehouse->id, 'name' => 'Nuevo nombre']);
    }

    public function test_puede_eliminar_un_almacen(): void
    {
        $user = User::factory()->create();
        $warehouse = Warehouse::factory()->create();

        $response = $this->actingAs($user)->delete("/warehouses/{$warehouse->id}");

        $response->assertRedirect();
        // Sin SoftDeletes en esta tabla
        $this->assertDatabaseMissing('warehouses', ['id' => $warehouse->id]);
    }

    public function test_un_invitado_no_puede_crear_almacenes(): void
    {
        $response = $this->post('/warehouses', ['name' => 'Sucursal Centro']);

        $response->assertRedirect('/login');
    }
}