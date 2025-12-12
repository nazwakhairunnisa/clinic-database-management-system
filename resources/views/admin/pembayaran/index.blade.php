@extends('layouts.admin')

@section('pageTitle', 'Menunggu Pembayaran')

@section('content')

{{-- Iconify --}}
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<div class="max-w-full font-['Roboto',sans-serif]">

    {{-- SUCCESS/ERROR MESSAGE --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Menunggu Pembayaran</h1>
            <p class="text-sm text-gray-500 mt-1">Treatment yang sudah selesai dan menunggu pembayaran</p>
        </div>

        <div class="flex gap-2 mt-4 sm:mt-0">
            <a href="{{ route('admin.pembayaran.riwayat') }}" 
                class="flex items-center justify-center bg-gray-500 text-white px-4 py-2 rounded-full hover:bg-gray-600 text-sm shadow-sm">
                <iconify-icon icon="mdi:history" class="text-xl mr-1"></iconify-icon>
                Riwayat Pembayaran
            </a>
        </div>
    </div>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('admin.pembayaran.index') }}" id="filterForm">
        <div class="flex flex-wrap gap-2 sm:gap-3 items-center mb-6">
            {{-- Search --}}
            <div class="relative flex-grow sm:flex-grow-0">
                <input type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama pasien..."
                    class="border border-gray-300 rounded-full pl-10 pr-4 h-9 w-60 text-sm focus:ring-2 focus:ring-blue-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
            </div>

            {{-- DATE --}}
            <div class="relative">
                <input 
                    id="dateInput" 
                    name="date"
                    type="date"
                    value="{{ request('date') }}"
                    class="border border-gray-300 rounded-full pl-10 pr-4 h-9 text-sm cursor-pointer focus:ring-2 focus:ring-blue-500"
                    onchange="document.getElementById('filterForm').submit()">
                <iconify-icon 
                    icon="tabler:calendar"
                    class="text-gray-600 text-base absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                </iconify-icon>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 text-sm shadow-sm">
                <iconify-icon icon="mdi:filter" class="text-xl mr-1"></iconify-icon>
                Filter
            </button>

            @if(request()->anyFilled(['search', 'date']))
            <a href="{{ route('admin.pembayaran.index') }}" 
                class="bg-gray-500 text-white px-4 py-2 rounded-full hover:bg-gray-600 text-sm shadow-sm">
                <iconify-icon icon="mdi:filter-remove" class="text-xl mr-1"></iconify-icon>
                Clear
            </a>
            @endif
        </div>
    </form>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto">
        @if($pembayaran->count() > 0)
        <table class="min-w-[700px] w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-100/60 text-gray-700">
                    <th class="px-4 py-3 text-left rounded-l-lg border-b border-gray-300 font-light">No</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">ID Reservasi</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Pasien</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Treatment</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Total Tagihan</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Waktu Selesai</th>
                    <th class="px-4 py-3 text-left rounded-r-lg border-b border-gray-300 font-light">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @foreach($pembayaran as $index => $item)
                <tr class="bg-white hover:bg-gray-50 border-b border-gray-200">
                    <td class="px-4 py-3">{{ $pembayaran->firstItem() + $index }}</td>
                    <td class="px-4 py-3 font-medium">
                        R-{{ str_pad($item->reservasi->id_reservasi, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-4 py-3">
                        <div>
                            <div class="font-medium">
                                {{ $item->reservasi->pasien->nama_depan }} {{ $item->reservasi->pasien->nama_belakang }}
                            </div>
                            <div class="text-xs text-gray-500">{{ $item->reservasi->pasien->no_telepon }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="max-w-[200px]">
                            {{ $item->reservasi->treatments_list }}
                            @if($item->reservasi->detailReservasi->count() > 1)
                            <span class="text-xs text-gray-500">({{ $item->reservasi->detailReservasi->count() }} items)</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-semibold text-blue-600">
                            Rp {{ number_format($item->total_pembayaran, 0, ',', '.') }}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-xs text-gray-500">
                            {{ $item->updated_at->diffForHumans() }}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.pembayaran.show', $item->id_pembayaran) }}" 
                                class="bg-green-600 text-white px-3 py-1.5 rounded-lg hover:bg-green-700 text-xs font-medium">
                                <iconify-icon icon="mdi:cash-register" class="text-lg mr-1"></iconify-icon>
                                Proses Bayar
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $pembayaran->links() }}
        </div>

        @else
        <div class="text-center py-12 text-gray-500">
            <iconify-icon icon="mdi:cash-clock" class="text-6xl mb-2 text-gray-300"></iconify-icon>
            <p class="text-lg font-medium">Tidak ada pembayaran yang menunggu</p>
            <p class="text-sm mt-1">Semua transaksi sudah diproses</p>
        </div>
        @endif
    </div>

    {{-- SUMMARY CARD --}}
    @if($pembayaran->count() > 0)
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-5 rounded-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Menunggu</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $pembayaran->total() }}</h3>
                    <p class="text-xs mt-1 opacity-75">Transaksi</p>
                </div>
                <iconify-icon icon="mdi:clock-alert" class="text-5xl opacity-30"></iconify-icon>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-5 rounded-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Tagihan</p>
                    <h3 class="text-2xl font-bold mt-1">
                        Rp {{ number_format($pembayaran->sum('total_pembayaran') / 1000, 0) }}K
                    </h3>
                    <p class="text-xs mt-1 opacity-75">Menunggu dibayar</p>
                </div>
                <iconify-icon icon="mdi:cash-multiple" class="text-5xl opacity-30"></iconify-icon>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-5 rounded-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Rata-rata</p>
                    <h3 class="text-2xl font-bold mt-1">
                        Rp {{ number_format($pembayaran->avg('total_pembayaran') / 1000, 0) }}K
                    </h3>
                    <p class="text-xs mt-1 opacity-75">Per transaksi</p>
                </div>
                <iconify-icon icon="mdi:chart-line" class="text-5xl opacity-30"></iconify-icon>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection