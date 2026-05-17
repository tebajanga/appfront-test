<?php

namespace Tests\Feature;

use App\Jobs\SendPriceChangeNotification;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UpdateProductCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_updates_product_price(): void
    {
        Queue::fake();

        $product = Product::factory()->create([
            'price' => 100,
        ]);

        $this->artisan('product:update', [
            'id' => $product->id,
            '--price' => 250,
        ])->assertSuccessful();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'price' => 250,
        ]);

        Queue::assertPushed(SendPriceChangeNotification::class);
    }

    public function test_command_rejects_negative_price(): void
    {
        $product = Product::factory()->create([
            'price' => 100,
        ]);

        $this->artisan('product:update', [
            'id' => $product->id,
            '--price' => -50,
        ])->assertFailed();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'price' => 100,
        ]);
    }

    public function test_command_fails_when_product_does_not_exist(): void
    {
        $this->artisan('product:update', [
            'id' => 999999,
            '--price' => 100,
        ])->assertFailed();
    }
}
