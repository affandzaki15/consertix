<!-- Shared footer partial copied from concerts footer -->
<footer class="w-full bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">

            <!-- Logo + Tagline -->
            <div class="col-span-1">
                <div class="flex items-center space-x-3 mb-4">
                    <img src="{{ asset('logo/header.png') }}" class="h-12 w-auto">
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Platform tiket konser terpercaya untuk pengalaman pertunjukan yang tak terlupakan.
                </p>
                <div class="flex space-x-3 mt-6">
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

            <!-- Tentang Kami -->
            <div>
                <h3 class="font-bold text-lg mb-5 text-white">Tentang Kami</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white text-sm transition duration-300">Tentang Kami</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white text-sm transition duration-300">Hubungi Kami</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm transition duration-300">Blog</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm transition duration-300">Karir</a></li>
                </ul>
            </div>

            <!-- Informasi -->
            <div>
                <h3 class="font-bold text-lg mb-5 text-white">Informasi</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm transition duration-300">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm transition duration-300">Kebijakan Privasi</a></li>
                    <li><a href="{{ route('faq') }}" class="text-gray-400 hover:text-white text-sm transition duration-300">FAQ</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm transition duration-300">Pusat Bantuan</a></li>
                </ul>
            </div>

        </div>

        <!-- Divider -->
        <div class="border-t border-gray-700"></div>

        <!-- Bottom Section -->
        <div class="mt-8 pt-8 flex flex-col md:flex-row items-center justify-between">
            <p class="text-gray-400 text-sm">
                © 2025 Concertix. Semua hak dilindungi.
            </p>
            <div class="flex space-x-6 mt-6 md:mt-0 text-xs text-gray-400">
                <a href="#" class="hover:text-white transition duration-300">Privasi</a>
                <a href="#" class="hover:text-white transition duration-300">Terms</a>
                <a href="#" class="hover:text-white transition duration-300">Cookies</a>
            </div>
        </div>
    </div>
</footer>

<!-- End shared footer -->
