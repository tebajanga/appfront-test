@extends('layouts.admin')

@section('title', 'Add New Product')

@section('width-class', 'max-width-800')

@section('admin-content')
    <h1>Add New Product</h1>

    @include('layouts.partials.errors_list')

    <form action="{{ route('admin.add.product.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" required>{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" step="0.01" class="form-control" value="{{ old('price') }}" required>
        </div>

        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" id="image" name="image" class="form-control">
            <small>Leave empty to use default image</small>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Add Product</button>
            <a href="{{ route('admin.products') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection