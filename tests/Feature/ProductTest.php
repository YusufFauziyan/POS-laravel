<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => UserRole::ADMIN]);
        $this->category = Category::factory()->create();
        Storage::fake('public');
    }

    /**
     * Test product index page can be rendered.
     */
    public function test_product_index_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);
    }

    /**
     * Test product create page can be rendered.
     */
    public function test_product_create_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->user)->get('/products/create');

        $response->assertStatus(200);
    }

    /**
     * Test product can be created without image.
     */
    public function test_product_can_be_created_without_image(): void
    {
        $response = $this->actingAs($this->user)->post('/products', [
            'name' => 'Test Product',
            'category_id' => $this->category->id,
            'price' => 10000,
            'cost' => 5000,
            'stock' => 100,
            'is_active' => true,
        ]);

        $response->assertRedirect('/products');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'category_id' => $this->category->id,
            'price' => 10000,
            'cost' => 5000,
            'stock' => 100,
        ]);
    }

    /**
     * Test product can be created with image.
     */
    public function test_product_can_be_created_with_image(): void
    {
        $file = UploadedFile::fake()->image('product.jpg');

        $response = $this->actingAs($this->user)->post('/products', [
            'name' => 'Test Product',
            'category_id' => $this->category->id,
            'price' => 10000,
            'stock' => 100,
            'image' => $file,
            'is_active' => true,
        ]);

        $response->assertRedirect('/products');

        $product = Product::where('name', 'Test Product')->first();
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);
    }

    /**
     * Test product name is required.
     */
    public function test_product_name_is_required(): void
    {
        $response = $this->actingAs($this->user)->post('/products', [
            'name' => '',
            'category_id' => $this->category->id,
            'price' => 10000,
            'stock' => 100,
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test product category is required.
     */
    public function test_product_category_is_required(): void
    {
        $response = $this->actingAs($this->user)->post('/products', [
            'name' => 'Test Product',
            'category_id' => '',
            'price' => 10000,
            'stock' => 100,
        ]);

        $response->assertSessionHasErrors('category_id');
    }

    /**
     * Test product price is required and must be numeric.
     */
    public function test_product_price_validation(): void
    {
        $response = $this->actingAs($this->user)->post('/products', [
            'name' => 'Test Product',
            'category_id' => $this->category->id,
            'price' => 'invalid',
            'stock' => 100,
        ]);

        $response->assertSessionHasErrors('price');
    }

    /**
     * Test product stock is required and must be integer.
     */
    public function test_product_stock_validation(): void
    {
        $response = $this->actingAs($this->user)->post('/products', [
            'name' => 'Test Product',
            'category_id' => $this->category->id,
            'price' => 10000,
            'stock' => 'invalid',
        ]);

        $response->assertSessionHasErrors('stock');
    }

    /**
     * Test product image must be valid image file.
     */
    public function test_product_image_must_be_valid_image(): void
    {
        $file = UploadedFile::fake()->create('document.pdf');

        $response = $this->actingAs($this->user)->post('/products', [
            'name' => 'Test Product',
            'category_id' => $this->category->id,
            'price' => 10000,
            'stock' => 100,
            'image' => $file,
        ]);

        $response->assertSessionHasErrors('image');
    }

    /**
     * Test product edit page can be rendered.
     */
    public function test_product_edit_page_can_be_rendered(): void
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->user)->get("/products/{$product->id}/edit");

        $response->assertStatus(200);
    }

    /**
     * Test product can be updated.
     */
    public function test_product_can_be_updated(): void
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->user)->put("/products/{$product->id}", [
            'name' => 'Updated Product',
            'category_id' => $this->category->id,
            'price' => 20000,
            'cost' => 10000,
            'stock' => 50,
            'is_active' => false,
        ]);

        $response->assertRedirect('/products');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'price' => 20000,
            'stock' => 50,
        ]);
    }

    /**
     * Test product image is replaced when updating.
     */
    public function test_product_image_is_replaced_when_updating(): void
    {
        $oldFile = UploadedFile::fake()->image('old.jpg');
        
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'image' => $oldFile->store('products', 'public'),
        ]);

        $oldImage = $product->image;
        $newFile = UploadedFile::fake()->image('new.jpg');

        $response = $this->actingAs($this->user)->put("/products/{$product->id}", [
            'name' => $product->name,
            'category_id' => $this->category->id,
            'price' => $product->price,
            'stock' => $product->stock,
            'image' => $newFile,
        ]);

        $product->refresh();
        
        $this->assertNotEquals($oldImage, $product->image);
        Storage::disk('public')->assertExists($product->image);
        Storage::disk('public')->assertMissing($oldImage);
    }

    /**
     * Test product can be deleted.
     */
    public function test_product_can_be_deleted(): void
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->user)->delete("/products/{$product->id}");

        $response->assertRedirect('/products');

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    /**
     * Test product with orders cannot be deleted.
     */
    public function test_product_with_orders_cannot_be_deleted(): void
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);
        OrderItem::factory()->create(['product_id' => $product->id]);

        $response = $this->actingAs($this->user)->delete("/products/{$product->id}");

        $response->assertSessionHas('error');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
    }

    /**
     * Test product image is deleted when product is deleted.
     */
    public function test_product_image_is_deleted_when_product_is_deleted(): void
    {
        $file = UploadedFile::fake()->image('product.jpg');
        
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'image' => $file->store('products', 'public'),
        ]);

        $imagePath = $product->image;

        $this->actingAs($this->user)->delete("/products/{$product->id}");

        Storage::disk('public')->assertMissing($imagePath);
    }

    /**
     * Test product search functionality.
     */
    public function test_product_search_functionality(): void
    {
        Product::factory()->create([
            'name' => 'Cappuccino',
            'category_id' => $this->category->id,
        ]);
        Product::factory()->create([
            'name' => 'Espresso',
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->get('/products?search=Cappuccino');

        $response->assertStatus(200);
    }

    /**
     * Test product filter by category.
     */
    public function test_product_filter_by_category(): void
    {
        $category2 = Category::factory()->create();
        
        Product::factory()->create(['category_id' => $this->category->id]);
        Product::factory()->create(['category_id' => $category2->id]);

        $response = $this->actingAs($this->user)->get("/products?category={$this->category->id}");

        $response->assertStatus(200);
    }

    /**
     * Test product stock management methods.
     */
    public function test_product_stock_management(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock' => 100,
        ]);

        // Test has sufficient stock
        $this->assertTrue($product->hasSufficientStock(50));
        $this->assertFalse($product->hasSufficientStock(150));

        // Test reduce stock
        $product->reduceStock(30);
        $this->assertEquals(70, $product->fresh()->stock);

        // Test increase stock
        $product->increaseStock(20);
        $this->assertEquals(90, $product->fresh()->stock);
    }
}
