<div class="admin-header">
    <h1>@yield('admin-header-title')</h1>
    <div>
        <a href="{{ route('admin.add.product') }}" class="btn btn-primary">Add New Product</a>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf

            <button type="submit" class="btn btn-secondary form-button-reset">
                Logout
            </button>
        </form>
    </div>
</div>