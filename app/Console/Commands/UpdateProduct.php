<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Console\Command;

class UpdateProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:update {id} {--name=} {--description=} {--price=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a product with the specified details';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private readonly ProductService $productService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $product = Product::find($this->argument('id'));

        if (! $product) {
            $this->error('Product not found.');

            return self::FAILURE;
        }

        $data = $this->getUpdateData();

        if ($data === []) {
            $this->info('No changes provided. Product remains unchanged.');

            return self::SUCCESS;
        }

        $this->productService->update($product, $data);

        $this->info('Product updated successfully.');

        return self::SUCCESS;
    }

    /**
     * Collect and validate update data from command options.
     */
    private function getUpdateData(): array
    {
        $data = [];

        if ($this->option('name') !== null) {
            $name = trim((string) $this->option('name'));

            if ($name === '') {
                $this->fail('Name cannot be empty.');
            }

            if (strlen($name) < 3) {
                $this->fail('Name must be at least 3 characters long.');
            }

            $data['name'] = $name;
        }

        if ($this->option('description') !== null) {
            $data['description'] = trim((string) $this->option('description'));
        }

        if ($this->option('price') !== null) {
            $price = (float) $this->option('price');

            if ($price < 0) {
                $this->fail('Price cannot be negative.');
            }

            $data['price'] = $price;
        }

        return $data;
    }
}
