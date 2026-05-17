<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendPriceChangeNotification;

class AdminController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function loginPage()
    {
        return view('login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->except('_token'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.products');
        }

        return redirect()->back()->with('error', 'Invalid login credentials');
    }

    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }

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
