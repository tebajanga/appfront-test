@if(session('error'))
    <div class="error-message">
        {{ session('error') }}
    </div>
@endif