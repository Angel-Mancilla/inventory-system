<?php

namespace Tests\Feature\Brands;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_usuario_autenticado_puede_crear_una_marca(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/brands', [
            'name' => 'HP',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('brands', ['name' => 'HP']);
    }

    public function test_puede_actualizar_una_marca(): void
    {
        $user = User::factory()->create();
        $brand = Brand::factory()->create(['name' => 'Viejo nombre']);

        $response = $this->actingAs($user)->put("/brands/{$brand->id}", [
            'name' => 'Nuevo nombre',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('brands', ['id' => $brand->id, 'name' => 'Nuevo nombre']);
    }

    public function test_puede_eliminar_una_marca(): void
    {
        $user = User::factory()->create();
        $brand = Brand::factory()->create();

        $response = $this->actingAs($user)->delete("/brands/{$brand->id}");

        $response->assertRedirect();
        $this->assertSoftDeleted($brand);
    }

    public function test_un_invitado_no_puede_crear_marcas(): void
    {
        $response = $this->post('/brands', ['name' => 'HP']);

        $response->assertRedirect('/login');
    }

    public function test_el_nombre_es_obligatorio(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/brands', [
            'name' => '',
            'is_active' => true,
        ]);

        $response->assertSessionHasErrors('name');
    }
}