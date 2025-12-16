<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => UserRole::ADMIN]);
    }

    /**
     * Test category index page can be rendered.
     */
    public function test_category_index_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->user)->get('/categories');

        $response->assertStatus(200);
    }

    /**
     * Test category create page can be rendered.
     */
    public function test_category_create_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->user)->get('/categories/create');

        $response->assertStatus(200);
    }

    /**
     * Test category can be created.
     */
    public function test_category_can_be_created(): void
    {
        $response = $this->actingAs($this->user)->post('/categories', [
            'name' => 'Test Category',
            'description' => 'Test Description',
            'is_active' => true,
        ]);

        $response->assertRedirect('/categories');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'description' => 'Test Description',
            'is_active' => true,
        ]);
    }

    /**
     * Test category name is required.
     */
    public function test_category_name_is_required(): void
    {
        $response = $this->actingAs($this->user)->post('/categories', [
            'name' => '',
            'description' => 'Test Description',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test category name must be unique.
     */
    public function test_category_name_must_be_unique(): void
    {
        Category::factory()->create(['name' => 'Existing Category']);

        $response = $this->actingAs($this->user)->post('/categories', [
            'name' => 'Existing Category',
            'description' => 'Test Description',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test category edit page can be rendered.
     */
    public function test_category_edit_page_can_be_rendered(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->get("/categories/{$category->id}/edit");

        $response->assertStatus(200);
    }

    /**
     * Test category can be updated.
     */
    public function test_category_can_be_updated(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->put("/categories/{$category->id}", [
            'name' => 'Updated Category',
            'description' => 'Updated Description',
            'is_active' => false,
        ]);

        $response->assertRedirect('/categories');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category',
            'description' => 'Updated Description',
            'is_active' => false,
        ]);
    }

    /**
     * Test category can be deleted.
     */
    public function test_category_can_be_deleted(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->delete("/categories/{$category->id}");

        $response->assertRedirect('/categories');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * Test category with products cannot be deleted.
     */
    public function test_category_with_products_cannot_be_deleted(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->user)->delete("/categories/{$category->id}");

        $response->assertSessionHas('error');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * Test category search functionality.
     */
    public function test_category_search_functionality(): void
    {
        Category::factory()->create(['name' => 'Coffee']);
        Category::factory()->create(['name' => 'Tea']);

        $response = $this->actingAs($this->user)->get('/categories?search=Coffee');

        $response->assertStatus(200);
    }

    /**
     * Test only active categories are shown in active scope.
     */
    public function test_active_scope_filters_categories(): void
    {
        Category::factory()->create(['name' => 'Active Category', 'is_active' => true]);
        Category::factory()->create(['name' => 'Inactive Category', 'is_active' => false]);

        $activeCategories = Category::active()->get();

        $this->assertCount(1, $activeCategories);
        $this->assertEquals('Active Category', $activeCategories->first()->name);
    }
}
