<?php

namespace App\Services;

use App\Actions\UploadProductImage;
use App\Jobs\SendPriceChangeNotification;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public function __construct(
        private UploadProductImage $uploadProductImage
    ) {}

    public function create(array $data, ?UploadedFile $image = null): Product
    {
        return DB::transaction(function () use ($data, $image) {
            if ($image) {
                $data['image'] = $this->uploadProductImage->handle($image);
            } else {
                $data['image'] = 'product-placeholder.jpg';
            }

            return Product::create($data);
        });
    }

    public function update(Product $product, array $data, ?UploadedFile $image = null): Product
    {
        return DB::transaction(function () use ($product, $data, $image) {
            $oldPrice = $product->price;

            if ($image) {
                $data['image'] = $this->uploadProductImage->handle($image);
            }

            $product->update(Arr::except($data, ['image']) + Arr::only($data, ['image']));

            $product->refresh();

            if ((float) $oldPrice !== (float) $product->price) {
                $this->dispatchPriceChangeNotification($product, $oldPrice);
            }

            return $product;
        });
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    private function dispatchPriceChangeNotification(Product $product, float $oldPrice): void
    {
        try {
            SendPriceChangeNotification::dispatch(
                $product,
                $oldPrice,
                $product->price,
                config('services.notifications.price_change_email')
            );
        } catch (\Throwable $exception) {
            Log::error('Failed to dispatch price change notification', [
                'product_id' => $product->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
