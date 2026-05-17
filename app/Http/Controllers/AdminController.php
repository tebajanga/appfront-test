<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;

class AdminController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function products()
    {
        $products = Product::all();

        return view('admin.products', compact('products'));
    }

    public function editProduct(Product $product)
    {
        return view('admin.edit_product', compact('product'));
    }

    public function updateProduct(ProductRequest $request, Product $product)
    {
        $this->productService->update(
            $product,
            $request->safe()->except('image'),
            $request->file('image')
        );

        return redirect()->route('admin.products')->with('success', 'Product updated successfully');
    }

    public function deleteProduct(Product $product)
    {
        $this->productService->delete($product);

        return redirect()->route('admin.products')->with('success', 'Product deleted successfully');
    }

    public function addProductForm()
    {
        return view('admin.add_product');
    }

    public function addProduct(ProductRequest $request)
    {
        $this->productService->create(
            $request->safe()->except('image'),
            $request->file('image')
        );

        return redirect()->route('admin.products')->with('success', 'Product added successfully');
    }
}
