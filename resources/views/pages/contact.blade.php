@extends('layouts.app')

@section('title', 'Hubungi Kami')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
        <!-- Left column: contact info -->
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Hubungi Kami</h1>

            <p class="text-gray-600 mb-8">Tim kami siap membantu Anda sebaik mungkin.</p>

            <div class="space-y-6 text-gray-700">
                <div class="flex items-start gap-4">
                    <div class="text-indigo-600 mt-1">
                        <!-- home icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9.5L12 4l9 5.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V9.5z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">Alamat</div>
                        <div class="text-sm text-gray-600">Griya Karanganyar 2, Jl. Karanganyar Raya Blok A1, RT.01/RW.46 Karanganyar, Kec. Lowokwaru, Malang 55584</div>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="text-indigo-600 mt-1">
                        <!-- whatsapp icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.05A9 9 0 1111.95 3 8.96 8.96 0 0121 12.05z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.05 11.2c-.2-.1-1.2-.6-1.4-.6s-.4 0-.6.1c-.2.1-.5.6-.6.9s-.2.4-.4.4c-.2 0-.4-.1-1-.5-.4-.3-.7-.6-1.1-.6-.3 0-.7.1-1 .6-.3.5-.9 1.7.9 3.1 1.2 1 2.2 1.3 3 1.5.8.1 1.6.1 2.3-.4s1.9-1.6 2.2-2.6c.3-1-.3-1.3-.8-1.5z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">Telepon </div>
                        <div class="text-sm text-gray-600">+62 821-3767-6220</div>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="text-indigo-600 mt-1">
                        <!-- mail icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">Email</div>
                        <div class="text-sm text-gray-600">cs@concertix.co.id</div>
                    </div>
                </div>
            </div>

           
            <div class="mt-8">
                <a href="https://wa.me/085156799441" target="_blank" aria-label="Chat via WhatsApp" class="inline-flex items-center gap-3 bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-3 rounded-full shadow-md transition duration-200">
                    <i class="fa-brands fa-whatsapp fa-lg"></i>
                    <span>Chat via WhatsApp</span>
                </a>
            </div>
        </div>

        <!-- Right column: contact form card -->
        <div>
            <div class="bg-white rounded-xl shadow p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Terhubung dengan kami</h2>
                <p class="text-sm text-indigo-600 mb-6"><a href="#">Hubungi kami kapan saja</a></p>

                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    @if(session('success'))
                        <div class="mb-4 p-3 rounded bg-green-50 border border-green-100 text-green-800">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="mb-4 p-3 rounded bg-red-50 border border-red-100 text-red-800">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" placeholder="Masukkan nama lengkap Anda" class="mt-1 block w-full rounded-lg border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" placeholder="Masukkan email Anda" class="mt-1 block w-full rounded-lg border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
                        </div>

                        

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pesan</label>
                            <textarea name="message" rows="5" placeholder="Masukkan pesan anda" class="mt-1 block w-full rounded-lg border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                        </div>

                        <div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-lg transition">Kirim Pesan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')
@endsection
