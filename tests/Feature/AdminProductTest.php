<?php

namespace Tests\Feature;

use App\Jobs\SendPriceChangeNotification;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_products_page(): void
    {
        $response = $this->get(route('admin.products'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_update_product(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 100,
        ]);

        $response = $this->actingAs($user)->put(route('admin.update.product', $product), [
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'price' => 150,
        ]);

        $response->assertRedirect(route('admin.products'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'price' => 150,
        ]);

        Queue::assertPushed(SendPriceChangeNotification::class);
    }

    public function test_negative_product_price_is_rejected(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 100,
        ]);

        $response = $this->actingAs($user)->from(route('admin.edit.product', $product))
            ->put(route('admin.update.product', $product), [
                'name' => 'Product Name',
                'description' => 'Description',
                'price' => -20,
            ]);

        $response->assertRedirect(route('admin.edit.product', $product));
        $response->assertSessionHasErrors('price');
    }

    public function test_admin_can_upload_product_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $product = Product::factory()->create();

        $image = UploadedFile::fake()->image('product.jpg');

        $response = $this->actingAs($user)->put(route('admin.update.product', $product), [
            'name' => 'Product Name',
            'description' => 'Description',
            'price' => 200,
            'image' => $image,
        ]);

        $response->assertRedirect(route('admin.products'));

        $product->refresh();

        Storage::disk('public')->assertExists($product->image);
    }

    public function test_invalid_image_file_is_rejected(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->from(route('admin.edit.product', $product))
            ->put(route('admin.update.product', $product), [
                'name' => 'Product Name',
                'description' => 'Description',
                'price' => 100,
                'image' => $file,
            ]);

        $response->assertRedirect(route('admin.edit.product', $product));
        $response->assertSessionHasErrors('image');
    }
}