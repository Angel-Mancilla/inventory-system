<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_usuario_autenticado_puede_crear_una_categoria(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/categories', [
            'name' => 'Laptops',
            'slug' => 'laptops',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'slug' => 'laptops',
            'name' => 'Laptops',
        ]);
    }

    public function test_no_permite_slug_duplicado(): void
    {
        $user = User::factory()->create();
        Category::factory()->create(['slug' => 'laptops']);

        $response = $this->actingAs($user)->post('/categories', [
            'name' => 'Laptops Gamer',
            'slug' => 'laptops',
            'is_active' => true,
        ]);

        $response->assertSessionHasErrors('slug');
        $this->assertDatabaseCount('categories', 1);
    }

    public function test_puede_actualizar_una_categoria(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['name' => 'Viejo nombre']);

        $response = $this->actingAs($user)->put("/categories/{$category->id}", [
            'name' => 'Nuevo nombre',
            'slug' => $category->slug,
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Nuevo nombre']);
    }

    public function test_puede_eliminar_una_categoria(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->delete("/categories/{$category->id}");

        $response->assertRedirect();
        $this->assertSoftDeleted($category);
    }

    public function test_un_invitado_no_puede_crear_categorias(): void
    {
        $response = $this->post('/categories', ['name' => 'Laptops', 'slug' => 'laptops']);

        $response->assertRedirect('/login');
    }
}
