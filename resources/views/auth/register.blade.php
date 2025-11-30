@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Image (right on md+) -->
    <div class="hidden md:flex md:w-1/2 bg-indigo-700 text-white items-center justify-center md:order-2" style="background-image: url('/logo/login.png'); background-size: cover; background-position: center;">
        <div class="w-full h-full text-center p-8 bg-black/50 flex items-center justify-center rounded-l-xl">
            <div class="max-w-lg">
                <h1 class="text-4xl font-extrabold">Create an account</h1>
                <p class="mt-4 text-gray-100">Sign up to access all features</p>
            </div>
        </div>
    </div>

    <!-- Right: form -->
    <div class="flex-1 flex items-center justify-center p-8 bg-white md:order-1">
        <div class="w-full max-w-md">
            <div class="mb-6 text-center">
                <div class="flex justify-center mb-4">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('logo/logoblack.png') }}" alt="Logo" class="h-12">
                    </a>
                </div>
                <h2 class="text-2xl font-bold">Create an account</h2>
                <p class="text-sm text-gray-500">Sign up to access all features</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4 bg-white">
                @csrf

                <div>
                    <label for="name" class="text-sm font-medium text-gray-700">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="mt-1 block w-full rounded-full px-4 py-3 border border-gray-200 focus:ring-2 focus:ring-indigo-300" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="mt-1 block w-full rounded-full px-4 py-3 border border-gray-200 focus:ring-2 focus:ring-indigo-300" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="phone" class="text-sm font-medium text-gray-700">Phone</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required autocomplete="tel" class="mt-1 block w-full rounded-full px-4 py-3 border border-gray-200 focus:ring-2 focus:ring-indigo-300" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                {{-- role selection removed --}}

                <div>
                    <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                    <div class="relative mt-1">
                        <input id="password" name="password" type="password" required autocomplete="new-password" class="block w-full rounded-full px-4 py-3 pr-12 border border-gray-200 focus:ring-2 focus:ring-indigo-300" />
                        <button type="button" id="togglePassword" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none" title="Toggle password visibility">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <label for="password_confirmation" class="text-sm font-medium text-gray-700">Confirm Password</label>
                    <div class="relative mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="block w-full rounded-full px-4 py-3 pr-12 border border-gray-200 focus:ring-2 focus:ring-indigo-300" />
                        <button type="button" id="togglePasswordConfirm" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none" title="Toggle password visibility">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-full font-semibold text-lg">Register</button>

                    <div class="mt-4 text-center text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-indigo-600 font-semibold ml-1">Log In</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePasswordBtn = document.getElementById('togglePassword');
    const togglePasswordConfirmBtn = document.getElementById('togglePasswordConfirm');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');

    function setupToggle(btn, input) {
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                
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
    }

    setupToggle(togglePasswordBtn, passwordInput);
    setupToggle(togglePasswordConfirmBtn, passwordConfirmInput);
});
</script>

@endsection
