@extends('layouts.admin')

@section('pageTitle', 'Riwayat Pembayaran')

@section('content')

<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<div class="max-w-full font-['Roboto',sans-serif]">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Pembayaran</h1>
            <p class="text-sm text-gray-500 mt-1">Semua transaksi pembayaran yang sudah lunas</p>
        </div>

        <div class="flex gap-2 mt-4 sm:mt-0">
            <a href="{{ route('admin.pembayaran.index') }}" 
                class="flex items-center justify-center bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 text-sm shadow-sm">
                <iconify-icon icon="mdi:arrow-left" class="text-xl mr-1"></iconify-icon>
                Kembali
            </a>
            <button onclick="window.print()" 
                class="flex items-center justify-center bg-gray-600 text-white px-4 py-2 rounded-full hover:bg-gray-700 text-sm shadow-sm">
                <iconify-icon icon="mdi:printer" class="text-xl mr-1"></iconify-icon>
                Print
            </button>
        </div>
    </div>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('admin.pembayaran.riwayat') }}" id="filterForm">
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

            {{-- Date From --}}
            <div class="relative">
                <input 
                    name="date_from"
                    type="date"
                    value="{{ request('date_from') }}"
                    class="border border-gray-300 rounded-full pl-10 pr-4 h-9 text-sm cursor-pointer focus:ring-2 focus:ring-blue-500">
                <iconify-icon 
                    icon="tabler:calendar"
                    class="text-gray-600 text-base absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                </iconify-icon>
            </div>

            <span class="text-gray-500">s/d</span>

            {{-- Date To --}}
            <div class="relative">
                <input 
                    name="date_to"
                    type="date"
                    value="{{ request('date_to') }}"
                    class="border border-gray-300 rounded-full pl-10 pr-4 h-9 text-sm cursor-pointer focus:ring-2 focus:ring-blue-500">
                <iconify-icon 
                    icon="tabler:calendar"
                    class="text-gray-600 text-base absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                </iconify-icon>
            </div>

            {{-- Metode Pembayaran --}}
            <div class="relative">
                <select name="metode"
                    class="bg-gray-100 text-gray-700 pl-4 pr-10 h-9 rounded-full text-sm cursor-pointer border-0 focus:ring-2 focus:ring-blue-500"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">All Payment Methods</option>
                    <option value="cash" {{ request('metode') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="transfer" {{ request('metode') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="ewallet" {{ request('metode') == 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 text-sm shadow-sm">
                <iconify-icon icon="mdi:filter" class="text-xl mr-1"></iconify-icon>
                Filter
            </button>

            @if(request()->anyFilled(['search', 'date_from', 'date_to', 'metode']))
            <a href="{{ route('admin.pembayaran.riwayat') }}" 
                class="bg-gray-500 text-white px-4 py-2 rounded-full hover:bg-gray-600 text-sm shadow-sm">
                <iconify-icon icon="mdi:filter-remove" class="text-xl mr-1"></iconify-icon>
                Clear
            </a>
            @endif
        </div>
    </form>

    {{-- SUMMARY CARD --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-5 rounded-xl shadow-lg">
            <p class="text-sm opacity-90">Total Pemasukan</p>
            <h3 class="text-2xl font-bold mt-1">
                Rp {{ number_format($totalPemasukan / 1000, 0) }}K
            </h3>
            <p class="text-xs mt-1 opacity-75">Periode filter</p>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-5 rounded-xl shadow-lg">
            <p class="text-sm opacity-90">Total Transaksi</p>
            <h3 class="text-3xl font-bold mt-1">{{ $riwayat->total() }}</h3>
            <p class="text-xs mt-1 opacity-75">Pembayaran lunas</p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-5 rounded-xl shadow-lg">
            <p class="text-sm opacity-90">Rata-rata</p>
            <h3 class="text-2xl font-bold mt-1">
                Rp {{ $riwayat->count() > 0 ? number_format($riwayat->avg('total_pembayaran') / 1000, 0) : 0 }}K
            </h3>
            <p class="text-xs mt-1 opacity-75">Per transaksi</p>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-5 rounded-xl shadow-lg">
            <p class="text-sm opacity-90">Hari ini</p>
            <h3 class="text-3xl font-bold mt-1">
                {{ $riwayat->where('tanggal_pembayaran', today())->count() }}
            </h3>
            <p class="text-xs mt-1 opacity-75">Transaksi</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto">
        @if($riwayat->count() > 0)
        <table class="min-w-[800px] w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-100/60 text-gray-700">
                    <th class="px-4 py-3 text-left rounded-l-lg border-b border-gray-300 font-light">No</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Tanggal</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">ID Reservasi</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Pasien</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Treatment</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Metode</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Total</th>
                    <th class="px-4 py-3 text-left rounded-r-lg border-b border-gray-300 font-light">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @foreach($riwayat as $index => $item)
                <tr class="bg-white hover:bg-gray-50 border-b border-gray-200">
                    <td class="px-4 py-3">{{ $riwayat->firstItem() + $index }}</td>
                    <td class="px-4 py-3">
                        <div class="text-xs">
                            {{ $item->tanggal_pembayaran->format('d M Y') }}
                        </div>
                    </td>
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
                        <div class="max-w-[200px] text-xs">
                            {{ Str::limit($item->reservasi->treatments_list, 40) }}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $item->metode_pembayaran == 'cash' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $item->metode_pembayaran == 'transfer' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $item->metode_pembayaran == 'ewallet' ? 'bg-purple-100 text-purple-700' : '' }}">
                            {{ ucfirst($item->metode_pembayaran) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-semibold text-green-600">
                            Rp {{ number_format($item->total_pembayaran, 0, ',', '.') }}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.pembayaran.riwayat.detail', $item->id_pembayaran) }}" 
                                class="text-blue-600 hover:text-blue-800"
                                title="Detail">
                                <iconify-icon icon="mdi:eye" class="text-xl"></iconify-icon>
                            </a>
                            <a href="{{ route('admin.pembayaran.struk', $item->id_pembayaran) }}" 
                                class="text-green-600 hover:text-green-800"
                                title="Print Struk"
                                target="_blank">
                                <iconify-icon icon="mdi:printer" class="text-xl"></iconify-icon>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $riwayat->links() }}
        </div>

        @else
        <div class="text-center py-12 text-gray-500">
            <iconify-icon icon="mdi:receipt-text-outline" class="text-6xl mb-2 text-gray-300"></iconify-icon>
            <p class="text-lg font-medium">Belum ada riwayat pembayaran</p>
            <p class="text-sm mt-1">Transaksi yang sudah lunas akan muncul di sini</p>
        </div>
        @endif
    </div>
</div>

@endsection