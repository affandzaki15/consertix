@extends('layouts.app')

@section('title', $concert->title)

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <img src="{{ $concert->image_url }}" alt="{{ $concert->title }}" class="w-full h-96 object-cover">

        <div class="p-6">
            <h1 class="text-3xl font-bold mb-2">{{ $concert->title }}</h1>
            <div class="flex items-center space-x-4 text-gray-600 mb-4">
                <div>📍 {{ $concert->location }}</div>
                <div>📅 {{ \Illuminate\Support\Carbon::parse($concert->date)->format('d M Y') }}</div>
                <div class="text-green-600 font-medium">{{ $concert->status }}</div>
            </div>

            <p class="text-gray-700 mb-6">Organizer: <strong>{{ $concert->organizer }}</strong></p>

            <div class="text-gray-800 mb-6">
                <div class="text-xs text-gray-500">Mulai dari</div>
                <div class="text-3xl font-extrabold text-orange-500">Rp. {{ number_format($concert->price, 0, ',', '.') }}</div>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ route('concerts.index') }}"
                    class="inline-flex items-center bg-gray-200 px-4 py-2 rounded-lg">Back</a>

                @guest
                {{-- Jika belum login, arahkan ke login --}}
                <a href="{{ route('login') }}"
                    class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg">
                    Buy Ticket
                </a>
                @else
                {{-- Jika sudah login, arahkan ke halaman pembelian --}}
                <a href="{{ route('purchase.show', $concert->id) }}"
                    class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg">
                    Buy Ticket
                </a>
                @endguest
            </div>

            <!-- Ticket options -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold mb-4">Pilihan Tiket</h2>

                @forelse($concert->ticketTypes as $type)
                    <div class="bg-white rounded-xl p-6 mb-4 border">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-indigo-600 font-medium">{{ $type->name }}</div>
                                <div class="mt-2 text-2xl font-extrabold text-gray-900">Rp. {{ number_format($type->price, 0, ',', '.') }}</div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <button type="button" class="decrease inline-flex items-center justify-center w-8 h-8 rounded-md bg-gray-100 text-gray-700">-</button>
                                <input type="text" value="0" readonly class="qty w-12 text-center text-lg font-medium bg-transparent" data-id="{{ $type->id }}" data-price="{{ $type->price }}">
                                <button type="button" class="increase inline-flex items-center justify-center w-8 h-8 rounded-md bg-indigo-600 text-white">+</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-gray-500">(Belum ada tipe tiket)</div>
                @endforelse

                <!-- Total and action -->
                <div class="mt-6 bg-white rounded-xl p-6 border flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500">Total</div>
                        <div id="totalAmount" class="text-2xl font-extrabold">Rp. 0</div>
                    </div>

                    <div>
                        @guest
                            <a href="{{ route('login') }}" class="inline-flex items-center bg-indigo-600 text-white px-5 py-3 rounded-xl">Pesan Sekarang</a>
                        @else
                            <button id="orderNow" class="inline-flex items-center bg-indigo-600 text-white px-5 py-3 rounded-xl">Pesan Sekarang</button>
                        @endguest
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    (function(){
        function formatRupiah(amount){
            return 'Rp. ' + new Intl.NumberFormat('id-ID').format(amount);
        }

        const increases = document.querySelectorAll('.increase');
        const decreases = document.querySelectorAll('.decrease');
        const qtys = document.querySelectorAll('.qty');
        const totalEl = document.getElementById('totalAmount');
        const orderBtn = document.getElementById('orderNow');

        function recalcTotal(){
            let total = 0;
            qtys.forEach(i => {
                const q = parseInt(i.value) || 0;
                const p = parseInt(i.dataset.price) || 0;
                total += q * p;
            });
            totalEl.textContent = formatRupiah(total);
            return total;
        }

        increases.forEach(btn => {
            btn.addEventListener('click', function(){
                const input = this.parentElement.querySelector('.qty');
                let v = parseInt(input.value) || 0;
                v++;
                input.value = v;
                recalcTotal();
            });
        });

        decreases.forEach(btn => {
            btn.addEventListener('click', function(){
                const input = this.parentElement.querySelector('.qty');
                let v = parseInt(input.value) || 0;
                if(v > 0) v--;
                input.value = v;
                recalcTotal();
            });
        });

        orderBtn && orderBtn.addEventListener('click', function(){
            const items = {};
            qtys.forEach(i => {
                const q = parseInt(i.value) || 0;
                if(q > 0) items[i.dataset.id] = q;
            });

            if(Object.keys(items).length === 0){
                alert('Pilih jumlah tiket terlebih dahulu');
                return;
            }

            // build query string tickets[id]=qty
            const params = new URLSearchParams();
            Object.keys(items).forEach(id => params.append(`tickets[${id}]`, items[id]));
            const url = "{{ route('purchase.show', $concert->id) }}" + '?' + params.toString();
            window.location.href = url;
        });

    })();
</script>
@endpush