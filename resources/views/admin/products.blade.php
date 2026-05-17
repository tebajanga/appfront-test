@extends('layouts.admin')

@section('title', 'Admin - Products')

@section('admin-content')
    @section('admin-header-title', 'Admin - Products')

    @include('layouts.partials.admin_header')

    @include('layouts.partials.success_message')

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    @if($product->image)
                        <img src="{{ $product->image_url }}" width="50" height="50" alt="{{ $product->name }}">
                    @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>
                    <a href="{{ route('admin.edit.product', $product->id) }}" class="btn btn-primary">Edit</a>
                    <form 
                        action="{{ route('admin.delete.product', $product->id) }}" 
                        method="POST" 
                        style="display: inline;"
                        onsubmit="return confirm('Are you sure you want to delete this product?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-secondary form-button-reset">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection