@extends('layouts.admin')

@section('title', 'Edit Product')

@section('width-class', 'max-width-800')

@section('admin-content')
    <h1>Edit Product</h1>

    @include('layouts.partials.errors_list')

    <form action="{{ route('admin.update.product', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" required>{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" step="0.01" class="form-control" value="{{ old('price', $product->price) }}" required>
        </div>

        <div class="form-group">
            <label for="image">Current Image</label>
            @if($product->image)
                <img src="{{ $product->image_url }}" class="product-image" alt="{{ $product->name }}">
            @endif
            <input type="file" id="image" name="image" class="form-control">
            <small>Leave empty to keep current image</small>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="{{ route('admin.products') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection