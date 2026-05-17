@extends('layouts.master')

@section('content')
    <div class="admin-container @yield('width-class', '')">
        @yield('admin-content')
    </div>
@endsection