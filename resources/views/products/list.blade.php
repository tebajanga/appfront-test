@extends('layouts.products')

@section('title', 'Products')

@section('product-content')
    <h1>Products</h1>

    <div class="products-grid">
        @forelse ($products as $product)
            <div class="product-card">
                @if ($product->image)
                    <img src="{{ $product->image_url }}" class="product-image-full" alt="{{ $product->name }}">
                @endif
                <div class="product-info">
                    <h2 class="product-title">{{ $product->name }}</h2>
                    <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                    <div class="price-container">
                        <span class="price-usd-min">${{ number_format($product->price, 2) }}</span>
                        <span class="price-eur-min">€{{ number_format($product->price * $exchangeRate, 2) }}</span>
                    </div>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-primary">View Details</a>
                </div>
            </div>
        @empty
            <div class="empty-message">
                <p>No products found.</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 20px; text-align: center; font-size: 0.9rem; color: #7f8c8d;">
        <p>@include('layouts.partials.exchange_rate_info', ['exchangeRate' => $exchangeRate])</p>
    </div>
@endsection
