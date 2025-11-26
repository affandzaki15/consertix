@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #fdfdfd 0%, #f8f9fa 100%);
    }

    .hero-bg {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
            url('https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
    }
</style>


<!-- Hero Section -->
<!-- Hero Section -->
<section class="w-full py-10 bg-gradient-to-b from-[#0d0f55] to-[#0a0c38]">
    <div class="max-w-7xl mx-auto px-4">

        <!-- Slider Container -->
        <div class="swiper myHeroSwiper relative">

            <div class="swiper-wrapper">

                <!-- Slide 1 -->
                <div class="swiper-slide">
                    <img src="https://assets.artatix.co.id/event/5733F6KNY0.png"
                        class="w-full rounded-3xl
                      max-h-[430px] md:h-[430px]
                      object-contain md:object-cover" />
                </div>

                <!-- Slide 2 -->
                <div class="swiper-slide">
                    <img src="https://staticassets.kiostix.com/banner/1763438823733_1761539197585_sigma.png"
                        class="w-full rounded-3xl
                      max-h-[430px] md:h-[430px]
                      object-contain md:object-cover" />
                </div>

                <!-- Slide 3 -->
                <div class="swiper-slide">
                    <img src="https://assets.artatix.co.id/event/event_6896f7c6e7526.jpg"
                        class="w-full rounded-3xl
                      max-h-[430px] md:h-[430px]
                      object-contain md:object-cover" />
                </div>

                <!-- Slide 4 -->
                <div class="swiper-slide">
                    <img src="https://assets.artatix.co.id/event/DT76BDFQ6J.jpg"
                        class="w-full rounded-3xl
                      max-h-[430px] md:h-[430px]
                      object-contain md:object-cover" />
                </div>

            </div>

            <!-- LEFT Arrow -->
            <div
                class="swiper-button-prev
               !left-2 sm:!left-4
               !top-1/2 -translate-y-1/2
               !flex !items-center !justify-center
               !bg-white/90 hover:!bg-white
               text-black
               !rounded-full
               !w-8 !h-8 sm:!w-10 sm:!h-10
               shadow-lg
               !z-10">
            </div>

            <!-- RIGHT Arrow -->
            <div
                class="swiper-button-next
               !right-2 sm:!right-4
               !top-1/2 -translate-y-1/2
               !flex !items-center !justify-center
               !bg-white/90 hover:!bg-white
               text-black
               !rounded-full
               !w-8 !h-8 sm:!w-10 sm:!h-10
               shadow-lg
               !z-10">
            </div>

            <!-- PAGINATION -->
            <div class="swiper-pagination !bottom-2"></div>

        </div>

    </div>
</section>


<!-- HERO SEARCH SECTION -->
<section class="w-full bg-white py-10">
    <div class="max-w-4xl mx-auto px-6">

        <!-- Search Box (opens dropdown results) -->
        <div class="relative">
            <div class="bg-white rounded-3xl shadow-lg flex items-center px-6 py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-gray-400 mr-3" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                </svg>

                <input id="liveSearchInput" aria-label="Search events" placeholder="Search event..."
                    class="w-full bg-transparent focus:outline-none text-lg text-gray-700 placeholder-gray-400 search-home" />
            </div>

            <!-- Dropdown below the search box -->
            <div id="liveSearchDropdown" class="hidden absolute left-0 right-0 mt-3 bg-white rounded-xl shadow-lg z-50 max-h-80 overflow-auto">
                <div id="liveSearchResults">
                    <div class="p-6 text-center text-gray-500">Type to search events...</div>
                </div>
            </div>
        </div>

    </div>
</section>
<!-- EVENT TERBARU SECTION -->
<section class="w-full bg-white py-14">
    <div class="max-w-7xl mx-auto px-6">

        <!-- Header with search -->
        <div class="flex items-center justify-between mb-6"> 
            <h2 class="text-3xl font-bold text-gray-900">Event Terbaru</h2>
        </div>

        <!-- Event List -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

            @forelse($concerts as $concert)
            <a href="{{ route('concerts.show', $concert->id) }}" class="block border rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                <img src="{{ $concert->image_url }}" class="w-full h-56 object-cover rounded-t-xl" />

                <div class="p-4">
                    <div class="flex items-center text-gray-600 text-sm space-x-2">
                        <span>📍 {{ $concert->location }}</span>
                    </div>

                    <div class="flex items-center text-gray-600 text-sm space-x-2">
                        <span>📅 {{ \Illuminate\Support\Carbon::parse($concert->date)->format('d M Y') }}</span>
                    </div>

                    <h3 class="mt-2 text-lg font-semibold">{{ $concert->title }}</h3>

                    <div class="mt-2 flex items-center justify-between">
                        <div>
                            <div class="text-xs text-gray-500">Mulai dari</div>
                            <div class="text-2xl font-extrabold text-orange-500">Rp. {{ number_format($concert->price, 0, ',', '.') }}</div>
                        </div>

                        <div class="text-green-600 font-medium text-sm">{{ $concert->status }}</div>
                    </div>

                    <!-- Organizer (logo + name) -->
                    <div class="border-t mt-4 pt-4 flex items-center space-x-3">
                        <img src="{{ $concert->image_url }}" alt="{{ $concert->organizer }}" class="h-10 w-10 rounded-full object-cover">
                        <div class="text-sm text-gray-700 font-medium">{{ $concert->organizer }}</div>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-1 sm:col-span-2 md:col-span-4">
                <div class="bg-white rounded-xl shadow p-8 text-center">
                    <span class="text-gray-400">(Belum ada data konser)</span>
                </div>
            </div>
            @endforelse

        </div>
    </div>
</section>

<!-- 4 EASY STEPS SECTIONudadadad aa-->
<section class="w-full py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        <!-- Left Title -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-16">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-3">
                    4 Easy Steps To Buy a Ticket!
                </h2>
                <p class="text-gray-500">
                    Get familiar with our 4 easy working process
                </p>
            </div>

            <!-- CTA Button -->
            <a href="{{ route('concerts.index') }}"
                class="mt-6 md:mt-0 inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl transition">
                Buy Ticket
                <span class="ml-2 text-lg">→</span>
            </a>
        </div>

        <!-- Steps Container -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 text-center">

            <!-- Step 1 (Choose A Concert) – kamu ganti gambarnya nanti -->
            <div class="flex flex-col items-center">
                <img src="{{ asset('logo/date.png') }}" alt="concert" class="h-32 mb-6">
                <h3 class="font-semibold text-lg mb-2">Choose A Concert</h3>
                <p class="text-gray-500 text-sm">
                    You can see concert tickets in our website and check which one is good for you.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="flex flex-col items-center">
                <img src="{{ asset('logo/datete.png') }}" alt="concert" class="h-32 mb-6">
                <h3 class="font-semibold text-lg mb-2">Choose Date & Time</h3>
                <p class="text-gray-500 text-sm">
                    You can check date and time of your favorite concert in our website.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="flex flex-col items-center">
                <img src="{{ asset('logo/paybil.png') }}" alt="concert" class="h-32 mb-6">
                <h3 class="font-semibold text-lg mb-2">Pay Your Bill</h3>
                <p class="text-gray-500 text-sm">
                    After choosing your date and preferred seat you can pay the ticket online.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="flex flex-col items-center">
                <img src="{{ asset('logo/download.png') }}" alt="concert" class="h-32 mb-6">
                <h3 class="font-semibold text-lg mb-2">Download Your Ticket!</h3>
                <p class="text-gray-500 text-sm">
                    After completings checkout, download your ticket and get ready for the event!
                </p>
            </div>

        </div>


    </div>
</section>

<!-- FAQ SECTION -->
<section class="w-full py-20 bg-white">
    <div class="max-w-5xl mx-auto px-6">

        <!-- Title -->
        <h2 class="text-4xl font-bold text-center text-gray-900 mb-4">
            Frequently Asked Questions
        </h2>
        <p class="text-center text-gray-600 mb-12">
            Temukan jawaban cepat untuk pertanyaan umum seputar layanan kami.
        </p>

        <!-- FAQ Container -->
        <div class="space-y-4">

            <!-- Item 1 -->
            <div class="border rounded-xl bg-white">
                <button onclick="toggleFAQ(1)"
                    class="w-full flex justify-between items-center px-6 py-4 text-left font-semibold text-gray-800">
                    Cara Membeli Tiket di Concertix
                    <span id="icon-1">+</span>
                </button>
                <div id="faq-1" class="hidden px-6 pb-4 text-gray-600">
                    Kamu bisa memilih konser, memilih jadwal, dan langsung melakukan pembayaran melalui platform kami.
                </div>
            </div>

            <!-- Item 2 -->
            <div class="border rounded-xl bg-white">
                <button onclick="toggleFAQ(2)"
                    class="w-full flex justify-between items-center px-6 py-4 text-left font-semibold text-gray-800">
                    Informasi Email Konfirmasi
                    <span id="icon-2">+</span>
                </button>
                <div id="faq-2" class="hidden px-6 pb-4 text-gray-600">
                    Email konfirmasi akan dikirim segera setelah pembayaran berhasil diproses.
                </div>
            </div>

            <!-- Item 3 -->
            <div class="border rounded-xl bg-white">
                <button onclick="toggleFAQ(3)"
                    class="w-full flex justify-between items-center px-6 py-4 text-left font-semibold text-gray-800">
                    Kebijakan Pembatalan dan Pengembalian Dana
                    <span id="icon-3">+</span>
                </button>
                <div id="faq-3" class="hidden px-6 pb-4 text-gray-600">
                    Pembatalan tiket mengikuti kebijakan promoter acara masing-masing.
                </div>
            </div>

            <!-- Item 4 -->
            <div class="border rounded-xl bg-white">
                <button onclick="toggleFAQ(4)"
                    class="w-full flex justify-between items-center px-6 py-4 text-left font-semibold text-gray-800">
                    Batas Waktu Pembayaran
                    <span id="icon-4">+</span>
                </button>
                <div id="faq-4" class="hidden px-6 pb-4 text-gray-600">
                    Kamu memiliki waktu 15 menit untuk menyelesaikan pembayaran setelah checkout.
                </div>
            </div>

            <!-- Item 5 -->
            <div class="border rounded-xl bg-white">
                <button onclick="toggleFAQ(5)"
                    class="w-full flex justify-between items-center px-6 py-4 text-left font-semibold text-gray-800">
                    Penukaran E-Ticket
                    <span id="icon-5">+</span>
                </button>
                <div id="faq-5" class="hidden px-6 pb-4 text-gray-600">
                    E-ticket dapat langsung digunakan untuk masuk ke venue tanpa perlu dicetak.
                </div>
            </div>

        </div>

    </div>
</section>



<!-- Footers t-->
{{-- <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; {{ date('Y') }} Concertix. All rights reserved.</p>
</div>
</footer> --}}
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
                <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                <li><a href="#" class="hover:text-white transition">Panduan Penyelenggara</a></li>
                <li><a href="#" class="hover:text-white transition">Hubungi Kami</a></li>
                <li><a href="#" class="hover:text-white transition">Biaya</a></li>
            </ul>
        </div>

        <!-- Informasi -->
        <div>
            <h3 class="font-semibold text-lg mb-3">Informasi</h3>
            <ul class="space-y-2 text-gray-300 text-sm">
                <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                <li><a href="#" class="hover:text-white transition">Kebijakan Privasi & Pemrosesan Data</a></li>
                <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                <li><a href="#" class="hover:text-white transition">Tiket Gelang</a></li>
            </ul>
        </div>

        <!-- Kategori -->
        <div>
            <h3 class="font-semibold text-lg mb-3">Kategori Event</h3>
            <ul class="space-y-2 text-gray-300 text-sm">
                <li><a href="#" class="hover:text-white transition">Musik</a></li>
                <li><a href="#" class="hover:text-white transition">Pameran</a></li>
                <li><a href="#" class="hover:text-white transition">Wahana</a></li>
                <li><a href="#" class="hover:text-white transition">Olahraga</a></li>
                <li><a href="#" class="hover:text-white transition">Semua Kategori</a></li>
            </ul>
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







<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    new Swiper(".myHeroSwiper", {
        loop: true,
        centeredSlides: true,
        slidesPerView: 1,
        spaceBetween: 20,
        autoplay: {
            delay: 4000, // 5 detik
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });
</script>
<script>
    function toggleFAQ(id) {
        const content = document.getElementById('faq-' + id);
        const icon = document.getElementById('icon-' + id);

        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.textContent = '−';
        } else {
            content.classList.add('hidden');
            icon.textContent = '+';
        }
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>

<script>
    // Live search dropdown behavior
    (function() {
        const input = document.getElementById('liveSearchInput');
        const dropdown = document.getElementById('liveSearchDropdown');
        const resultsEl = document.getElementById('liveSearchResults');
        let lastQuery = '';
        let controller = null;

        function openDropdown() {
            dropdown.classList.remove('hidden');
        }

        function closeDropdown() {
            dropdown.classList.add('hidden');
        }

        function renderResults(items) {
            if (!items || items.length === 0) {
                resultsEl.innerHTML = '<div class="p-6 text-center text-gray-500">No events found.</div>';
                return;
            }

            resultsEl.innerHTML = items.map(item => {
                const date = new Date(item.date).toLocaleDateString(undefined, {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
                // include thumbnail image on the left and organizer below
                return `\
                                            <a href="/concerts/${item.id}" class="block px-6 py-4 hover:bg-gray-50">\
                                    <div class="flex items-start space-x-4">\
                                        <img src="${escapeHtml(item.image_url)}" alt="" class="w-20 h-12 object-cover rounded-md flex-shrink-0">\
                                        <div class="flex-1">\
                                            <div class="font-semibold text-lg text-gray-900">${escapeHtml(item.title)}</div>\
                                            <div class="text-sm text-gray-500 mt-1">${date}</div>\
                                            <div class="text-sm text-gray-500">${escapeHtml(item.location)}</div>\
                                            <div class="text-sm text-gray-500 mt-2">Organizer: <span class="text-gray-700 font-medium">${escapeHtml(item.organizer || '')}</span></div>\
                                        </div>\
                                    </div>\
                                </a>\
                            `;
            }).join('');
        }

        function escapeHtml(str) {
            return String(str).replace(/[&<>"']/g, function(s) {
                return ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": "&#39;"
                })[s];
            });
        }

        function fetchResults(q) {
            if (controller) controller.abort();
            controller = new AbortController();
            lastQuery = q;
            if (!q || q.length < 1) {
                resultsEl.innerHTML = '<div class="p-6 text-center text-gray-500">Type to search events...</div>';
                return;
            }

            fetch("{{ route('concerts.search') }}?q=" + encodeURIComponent(q), {
                    signal: controller.signal
                })
                .then(r => r.json())
                .then(data => {
                    if (lastQuery !== q) return;
                    renderResults(data);
                    openDropdown();
                })
                .catch(err => {
                    if (err.name === 'AbortError') return;
                    console.error(err);
                });
        }

        input && input.addEventListener('focus', function() {
            if (input.value && input.value.length > 0) fetchResults(input.value);
            else openDropdown();
        });

        input && input.addEventListener('input', function(e) {
            fetchResults(e.target.value);
        });

        // close when clicking outside
        document.addEventListener('click', function(e) {
            const within = e.target.closest && (e.target.closest('#liveSearchDropdown') || e.target.closest('#liveSearchInput'));
            if (!within) closeDropdown();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDropdown();
        });
    })();
</script>
@endsection