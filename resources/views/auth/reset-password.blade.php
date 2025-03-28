@extends('layout.layout')

@section('content')
    <div class="container">
        <h2>Reset Password</h2>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror

            <label for="password">New Password:</label>
            <input type="password" name="password" required>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror

            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" name="password_confirmation" required>

            <button type="submit">Reset Password</button>
        </form>
    </div>
@endsection
