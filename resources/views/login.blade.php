@extends('layouts.master')

@section('title', 'Login')

@section('content')
    <div class="login-container">
        <h1>Admin Login</h1>

        @include('layouts.partials.error_message')

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control"
                       value="{{ app()->environment('local') ? 'test@example.com' : '' }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control"
                       value="{{ app()->environment('local') ? 'password' : '' }}" required>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>
@endsection