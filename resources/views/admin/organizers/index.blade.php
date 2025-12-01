@extends('layouts.app')

@section('content')
    <style>
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .organizer-row {
            opacity: 0;
            animation: fadeInLeft 0.5s ease-out forwards;
            animation-delay: calc(0.08s * var(--delay));
        }

        .organizer-row:hover {
            background-color: #f8fafc;
            transform: translateX(4px);
            transition: all 0.25s ease;
        }

        .action-btn {
            transition: all 0.2s ease;
            border-radius: 0.5rem;
        }
        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        }
    </style>

    @includeWhen(View::exists('admin.partials.menu'), 'admin.partials.menu')

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Modern -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Organizer Management</h1>
                    <p class="text-gray-600 mt-1">Kelola akun organizer dengan cepat dan aman.</p>
                </div>
                <a href="{{ route('admin.organizers.create') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-indigo-700 hover:bg-indigo-800 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Baru
                </a>
            </div>

            <!-- Empty State -->
            @if($organizers->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center max-w-2xl mx-auto">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Organizer</h3>
                    <p class="text-gray-600 mb-6">Mulai dengan menambahkan organizer pertama Anda hari ini.</p>
                    <a href="{{ route('admin.organizers.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                        Buat Sekarang
                    </a>
                </div>
            @else
                <!-- Modern Table Layout -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Organizer</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Terdaftar</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($organizers as $index => $user)
                                    <tr class="organizer-row hover:bg-gray-50 transition-colors" style="--delay: {{ $index * 0.08 }};">
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <span class="text-indigo-800 font-medium">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="text-sm text-gray-700">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('admin.organizers.edit', $user) }}"
                                               class="action-btn px-3 py-1.5 bg-teal-100 text-teal-800 hover:bg-teal-200 text-xs font-medium rounded-lg">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.organizers.destroy', $user) }}" method="POST" class="inline-block"
                                                  onsubmit="return confirm('Hapus organizer ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="action-btn px-3 py-1.5 bg-rose-100 text-rose-800 hover:bg-rose-200 text-xs font-medium rounded-lg">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($organizers->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $organizers->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Minimal Footer -->
    <footer class="mt-12 border-t border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} {{ config('app.name', 'EventFlow') }} — Powered by Creativity & Precision
        </div>
    </footer>
@endsection