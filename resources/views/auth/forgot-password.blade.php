@extends('layout.layout')

@section('content')
    <div class="container">
        <h2>Forgot Password</h2>
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label for="email">Enter your email:</label>
            <input type="email" name="email" required>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <button type="submit">Send Reset Link</button>
        </form>
    </div>
@endsection
