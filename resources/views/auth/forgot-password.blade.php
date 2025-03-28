@extends('layout.layout')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-sm bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-center mb-4">Forgot Password</h2>

            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 p-3 rounded mb-4 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Enter your email</label>
                    <input type="email" name="email" required
                        class="w-full px-3 py-2 border rounded focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition duration-200">
                    Send Reset Link
                </button>
            </form>
        </div>
    </div>
@endsection
