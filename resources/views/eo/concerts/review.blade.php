@extends('layouts.eo')

@section('content')
<div class="px-4 py-8 sm:px-6 lg:px-8">

    <div class="max-w-4xl mx-auto bg-white p-6 sm:p-8 rounded-2xl shadow-xl border border-gray-200">

        {{-- Title --}}
        <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-gray-900 mb-6">
            Review Konser 🎤
        </h2>

        {{-- Wrapper Poster + Info --}}
        <div class="flex flex-col md:grid md:grid-cols-2 gap-6">

            {{-- Poster --}}
            <div class="w-full">
                <img src="{{ asset('storage/' . $concert->image_url) }}"
                    class="rounded-xl w-full h-56 sm:h-72 md:h-80 object-cover border shadow-md"
                    alt="{{ $concert->title }}">
            </div>

            {{-- Info --}}
            <div class="space-y-4 text-gray-800">
                <div>
                    <p class="text-gray-500 text-xs sm:text-sm font-medium">Judul</p>
                    <p class="text-base sm:text-lg font-semibold">{{ $concert->title }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-xs sm:text-sm font-medium">Lokasi</p>
                    <p class="text-sm sm:text-base">{{ $concert->location }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-xs sm:text-sm font-medium">Tanggal</p>
                    <p class="text-sm sm:text-base">
                        {{ $concert->date->format('d M Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500 text-xs sm:text-sm font-medium">Status Approval</p>
                    <span class="px-3 py-1 text-xs rounded-full
                        @if($concert->approval_status === 'approved')
                            bg-green-100 text-green-700
                        @elseif($concert->approval_status === 'pending')
                            bg-yellow-100 text-yellow-700
                        @else
                            bg-gray-200 text-gray-700
                        @endif">
                        {{ ucfirst($concert->approval_status) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Description --}}
        @if($concert->description)
        <div class="mt-8">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">Deskripsi</h3>
            <p class="text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg text-sm sm:text-base">
                {{ $concert->description }}
            </p>
        </div>
        @endif

        {{-- Tickets List --}}
        <div class="mt-10">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Tipe Tiket 🎟️</h3>

            @if($concert->ticketTypes->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                @foreach($concert->ticketTypes as $t)
                <div class="p-4 rounded-lg border bg-white shadow-sm hover:shadow-md transition">
                    <h4 class="font-bold text-gray-900 text-sm sm:text-base">{{ $t->name }}</h4>
                    <p class="text-sm text-gray-600">Rp {{ number_format($t->price, 0, ',', '.') }}</p>
                    <p class="text-xs sm:text-sm text-gray-500">Kuota: {{ $t->quota }}</p>
                </div>
                @endforeach

            </div>
            @else
            <p class="text-gray-500 italic text-sm sm:text-base">
                Belum ada tiket ditambahkan.
            </p>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="mt-12 flex flex-col sm:flex-row justify-between gap-4">

            <a href="{{ route('eo.concerts.tickets.index', $concert->id) }}"
                class="w-full sm:w-auto text-center px-5 py-3 bg-gray-100 hover:bg-gray-200
                rounded-lg font-medium shadow-sm text-gray-800 transition">
                ← Kembali Edit Tiket
            </a>

            {{-- Button Open Modal --}}
            {{-- Submit --}}
            {{-- Submit --}}
            @if($concert->ticketTypes->count() > 0)

            @if($concert->approval_status === 'pending')
            <button disabled
                class="w-full sm:w-auto px-6 py-3 bg-yellow-400 text-yellow-900 font-semibold
            rounded-lg cursor-not-allowed opacity-80">
                Menunggu Approval ⏳
            </button>

            @elseif($concert->approval_status === 'approved')
            <button disabled
                class="w-full sm:w-auto px-6 py-3 bg-green-600 text-white font-semibold
            rounded-lg cursor-not-allowed">
                Approved ✔
            </button>

            @elseif($concert->approval_status === 'rejected')
            <button type="button" onclick="openApprovalModal()"
                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold
            rounded-lg shadow">
                Kirim Ulang ke Admin 🔁
            </button>

            {{-- Hidden Form Submit --}}
            <form id="approvalForm" action="{{ route('eo.concerts.submit', $concert->id) }}" method="POST" class="hidden">
                @csrf
            </form>

            @else
            {{-- Default: Belum Pernah Submit --}}
            <button type="button" onclick="openApprovalModal()"
                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow">
                Kirim ke Admin untuk Review 🚀
            </button>

            {{-- Hidden Form --}}
            <form id="approvalForm" action="{{ route('eo.concerts.submit', $concert->id) }}" method="POST" class="hidden">
                @csrf
            </form>

            @endif

            @else
            <button disabled
                class="w-full sm:w-auto px-6 py-3 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed opacity-70">
                Tambahkan Tiket terlebih dahulu
            </button>
            @endif





        </div>

    </div>

</div>


{{-- ================= MODAL CONFIRM ================= --}}
<div id="approvalModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white w-[90%] sm:w-[380px] rounded-xl shadow-lg p-6 text-center animate-modalShow">

        <h3 class="text-lg font-semibold text-gray-800 mb-3">
            Apakah Anda sudah yakin?
        </h3>
        <p class="text-sm text-gray-600 mb-6">
            Kirim konser ini ke admin untuk proses persetujuan.
        </p>

        <div class="flex justify-center gap-3">
            <button onclick="closeApprovalModal()"
                class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium">
                Batal
            </button>

            <button onclick="submitApproval()"
                class="px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium">
                Kirim
            </button>
        </div>

    </div>
</div>


{{-- SUCCESS TOAST --}}
<div id="successToast"
    class="hidden fixed top-5 right-5 bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg text-sm font-medium z-50">
    ✓ Konser berhasil dikirim ke admin!
</div>


{{-- LOADING OVERLAY --}}
<div id="loadingScreen"
    class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex-col gap-6 text-white text-xl flex items-center justify-center z-50">

    <div class="w-64 h-2 bg-gray-300 overflow-hidden rounded-full">
        <div id="progressBar" class="h-full bg-indigo-500 w-0"></div>
    </div>

    <strong class="animate-pulse text-center">Mengirim konser...</strong>
</div>



{{-- ================= SCRIPT ================= --}}
<script>
    function openApprovalModal() {
        document.getElementById('approvalModal').classList.remove('hidden');
        document.getElementById('approvalModal').classList.add('flex');
    }

    function closeApprovalModal() {
        document.getElementById('approvalModal').classList.add('hidden');
        document.getElementById('approvalModal').classList.remove('flex');
    }

      function submitApproval() {
        const loadingScreen = document.getElementById('loadingScreen');
        const progressBar = document.getElementById('progressBar');
        const form = document.getElementById('approvalForm');

        loadingScreen.classList.remove('hidden');

        let width = 0;
        const interval = setInterval(() => {
            width += 3;
            progressBar.style.width = width + "%";

            if (width >= 100) {
                clearInterval(interval);
                form.submit();
            }
        }, 40);
    }
</script>


{{-- ANIMATION STYLE --}}
<style>
    @keyframes modalShow {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-modalShow {
        animation: modalShow .25s ease-out forwards;
    }
</style>

@endsection