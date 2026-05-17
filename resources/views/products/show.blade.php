@extends('layouts.products')

@section('title', $product->name)

@section('product-content')
    <div class="product-detail">
        <div>
            @if ($product->image)
                <img src="{{ $product->image_url }}" class="product-detail-image">
            @endif
        </div>
        <div class="product-detail-info">
            <h1 class="product-detail-title">{{ $product->name }}</h1>
            <p class="product-id">Product ID: {{ $product->id }}</p>

            <div class="price-container">
                <span class="price-usd">${{ number_format($product->price, 2) }}</span>
                <span class="price-eur">€{{ number_format($product->price * $exchangeRate, 2) }}</span>
            </div>

            <div class="divider"></div>

            <div class="product-detail-description">
                <h4 class="description-title">Description</h4>
                <p>{{ $product->description }}</p>
            </div>

            <div class="action-buttons">
                <a href="{{ url('/') }}" class="btn btn-secondary">Back to Products</a>
                <button class="btn btn-primary">Add to Cart</button>
            </div>

            <p style="margin-top: 20px; font-size: 0.9rem; color: #7f8c8d;">
                @include('layouts.partials.exchange_rate_info', ['exchangeRate' => $exchangeRate])
            </p>
        </div>
    </div>
@endsection