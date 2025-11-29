@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Left: image/hero -->
    <div class="hidden md:flex md:w-1/2 bg-indigo-700 text-white items-center justify-center" style="background-image: url('/logo/concert-bg.jpg'); background-size: cover; background-position: center;">
        <div class="max-w-lg text-center p-8 bg-black/30 rounded-l-xl">
            <h1 class="text-4xl font-extrabold">Create an account</h1>
            <p class="mt-4 text-gray-100">Sign up to access all features</p>
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

                {{-- role selection removed as requested --}}

                <div>
                    <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password" class="mt-1 block w-full rounded-full px-4 py-3 border border-gray-200 focus:ring-2 focus:ring-indigo-300" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <label for="password_confirmation" class="text-sm font-medium text-gray-700">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="mt-1 block w-full rounded-full px-4 py-3 border border-gray-200 focus:ring-2 focus:ring-indigo-300" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-4">
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Already registered?</a>
                    <button type="submit" class="ms-4 w-full ml-3 bg-indigo-600 text-white py-3 rounded-full font-semibold">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
