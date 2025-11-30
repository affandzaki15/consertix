@extends('layouts.app')

@section('title', 'Hubungi Kami')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
        <!-- Left column: contact info -->
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Hubungi Kami</h1>
            <p class="text-gray-600 mb-6">Jika Anda memiliki pertanyaan seputar kerja sama atau mengalami kendala terkait tiket, silakan hubungi kami melalui kontak yang tersedia.</p>
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
                        <div class="text-sm text-gray-600">Griya Karanganyar 2, Jl. Karanganyar Raya Blok A1, RT.01/RW.46 Karanganyar, Wedomartani, Kec. Ngemplak, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55584</div>
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
                        <div class="text-sm font-medium text-gray-900">Telepon / WhatsApp</div>
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
                        <div class="text-sm text-gray-600">cs@artatix.co.id</div>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <div class="flex items-center space-x-4 text-gray-700">
                    <a href="#" class="text-xl hover:text-gray-900"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-xl hover:text-gray-900"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="text-xl hover:text-gray-900"><i class="fab fa-x-twitter"></i></a>
                    <a href="#" class="text-xl hover:text-gray-900"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="text-xl hover:text-gray-900"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="text-xl hover:text-gray-900"><i class="fab fa-facebook"></i></a>
                </div>
            </div>

            <div class="mt-8">
                <a href="https://wa.me/085156799441" target="_blank" class="inline-flex items-center gap-4 bg-gradient-to-r from-orange-400 via-indigo-600 to-purple-700 hover:shadow-lg text-white font-semibold px-8 py-4 rounded-full transition duration-300">
                    <svg class="w-10 h-10" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <!-- Character head -->
                        <circle cx="50" cy="35" r="18" fill="black"/>
                        <!-- Hat -->
                        <path d="M 35 20 Q 50 5 65 20 L 60 25 Q 50 15 40 25 Z" fill="black"/>
                        <!-- Eyes -->
                        <circle cx="45" cy="32" r="3" fill="white"/>
                        <circle cx="55" cy="32" r="3" fill="white"/>
                        <!-- Smile -->
                        <path d="M 45 38 Q 50 42 55 38" stroke="white" stroke-width="2" fill="none"/>
                        <!-- Body -->
                        <rect x="42" y="55" width="16" height="20" rx="4" fill="black"/>
                        <!-- Arms -->
                        <rect x="30" y="58" width="12" height="6" rx="3" fill="white"/>
                        <rect x="58" y="58" width="12" height="6" rx="3" fill="white"/>
                        <!-- Phone hand -->
                        <rect x="62" y="50" width="8" height="15" rx="2" fill="white" transform="rotate(-20 66 50)"/>
                    </svg>
                    <span>Chat WhatsApp</span>
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

<!-- FOOTER SECTION -->
<footer class="w-full bg-gray-900 text-white py-14">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-10">

        <!-- Logo + Tagline -->
        <div>
            <div class="flex items-center space-x-3 mb-3">
                <img src="{{ asset('logo/header.png') }}" class="h-10">
            </div>
            <p class="text-gray-300 text-sm">
                Your Professional Ticketing Partner
            </p>
        </div>

        <!-- Tentang Kami -->
        <div>
            <h3 class="font-semibold text-lg mb-3">Tentang Kami</h3>
            <ul class="space-y-2 text-gray-300 text-sm">
                <li><a href="{{ route('about') }}" class="hover:text-white transition">Tentang Kami</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-white transition">Hubungi Kami</a></li>
            </ul>
        </div>

        <!-- Informasi -->
        <div>
            <h3 class="font-semibold text-lg mb-3">Informasi</h3>
            <ul class="space-y-2 text-gray-300 text-sm">
                <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                <li><a href="#" class="hover:text-white transition">Kebijakan Privasi & Pemrosesan Data</a></li>
                <li><a href="#" class="hover:text-white transition">FAQ</a></li>
        </div>

       
    </div>

    <!-- Divider -->
    <div class="max-w-7xl mx-auto mt-10 border-t border-gray-500/30"></div>

    <!-- Bottom Section -->
    <div class="max-w-7xl mx-auto px-6 mt-6 flex flex-col md:flex-row items-center justify-between">

        <p class="text-gray-300 text-sm">
            © 2025 Concertix.
        </p>

        <div class="flex space-x-4 text-xl mt-4 md:mt-0">

            <a href="#" class="hover:text-gray-200 transition">
                <i class="fab fa-whatsapp"></i>
            </a>
            <a href="#" class="hover:text-gray-200 transition">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="hover:text-gray-200 transition">
                <i class="fab fa-tiktok"></i>
            </a>
            <a href="#" class="hover:text-gray-200 transition">
                <i class="fab fa-x-twitter"></i>
            </a>
            <a href="#" class="hover:text-gray-200 transition">
                <i class="fab fa-linkedin"></i>
            </a>
            <a href="#" class="hover:text-gray-200 transition">
                <i class="fab fa-youtube"></i>
            </a>
            <a href="#" class="hover:text-gray-200 transition">
                <i class="fab fa-facebook"></i>
            </a>

        </div>

    </div>
</footer>
@endsection
