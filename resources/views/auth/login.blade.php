@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Left: image/hero -->
    <div class="hidden md:flex md:w-1/2 bg-indigo-700 text-white items-center justify-center" style="background-image: url('/logo/concert-bg.jpg'); background-size: cover; background-position: center;">
        <div class="max-w-lg text-center p-8 bg-black/30 rounded-l-xl">
            <h1 class="text-4xl font-extrabold">Welcome Back</h1>
            <p class="mt-4 text-gray-100">Access your personal account by logging in</p>
        </div>
    </div>

    <!-- Right: form -->
    <div class="flex-1 flex items-center justify-center p-8 bg-white">
        <div class="w-full max-w-md">
            <div class="mb-6 text-center">
                <div class="flex justify-center mb-4">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('logo/header.png') }}" alt="Logo" class="h-12">
                    </a>
                </div>
                <h2 class="text-2xl font-bold">Welcome Back</h2>
                <p class="text-sm text-gray-500">Access your personal account by logging in</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4 bg-white">
                @csrf

                <div>
                    <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="mt-1 block w-full rounded-full px-4 py-3 border border-gray-200 focus:ring-2 focus:ring-indigo-300" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                    <div class="relative mt-1">
                        <input id="password" name="password" type="password" required autocomplete="current-password" class="block w-full rounded-full px-4 py-3 pr-12 border border-gray-200 focus:ring-2 focus:ring-indigo-300" />
                        <button type="button" id="togglePassword" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none" title="Toggle password visibility">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300">
                        <span class="ml-2 text-gray-600">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-indigo-600">Forgot Password</a>
                    @endif
                </div>

                <div>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-full font-semibold">Log in</button>
                </div>

                <div class="text-center text-sm text-gray-500 mt-3">
                    Don't have an account? <a href="{{ route('register') }}" class="text-indigo-600 font-semibold">Sign Up</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            
            const icon = this.querySelector('i');
            if (isPassword) {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }
});
</script>

@endsection
