@extends('layouts.owner.app') 

@section('pageTitle', 'Laporan Keuangan')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<div class="font-['Roboto',sans-serif] px-4 sm:px-6 lg:px-10 py-6">

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    {{-- ========================= --}}
    {{-- FILTER SECTION --}}
    {{-- ========================= --}}
    <div class="flex items-center justify-between mb-6 gap-4">
        
        {{-- Filter Bar (Kiri) --}}
        <form method="GET" action="{{ route('owner.laporan_penjualan') }}" id="filterForm" class="flex-1">
            <div class="flex items-center gap-3 bg-white rounded-full shadow-sm border border-gray-200 px-4 py-2 max-w-md">
                
                {{-- Filter Icon --}}
                <iconify-icon icon="mingcute:filter-line" class="text-gray-400 text-xl"></iconify-icon>
                
                {{-- Filter Bulan --}}
                <select name="bulan" 
                    class="border-none outline-none focus:ring-0 text-sm text-gray-700 bg-transparent cursor-pointer"
                    onchange="document.getElementById('filterForm').submit()">
                    @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                    @endforeach
                </select>

                <span class="text-gray-400">|</span>

                {{-- Filter Tahun --}}
                <select name="tahun" 
                    class="border-none outline-none focus:ring-0 text-sm text-gray-700 bg-transparent cursor-pointer"
                    onchange="document.getElementById('filterForm').submit()">
                    @foreach(range(date('Y'), date('Y') - 5) as $y)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                    @endforeach
                </select>

            </div>
        </form>

        {{-- Export Button (Kanan) --}}
        <a href="{{ route('owner.laporan_penjualan.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
            class="flex items-center gap-2 bg-[#806B3F] text-white px-6 py-2.5 rounded-full
            hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md whitespace-nowrap">
            <iconify-icon icon="bx:export" class="text-xl sm:mr-2"></iconify-icon>
            <span class="font-medium">Export</span>
        </a>

    </div>

    {{-- ========================= --}}
    {{-- SUMMARY CARDS --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

        {{-- CARD 1: Total Income --}}
        <div class="bg-white shadow rounded-xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Pemasukan</p>
                    <p class="font-semibold text-xl text-[#0A1A2A]">
                        Rp {{ number_format($stats['income']['value'], 0, ',', '.') }}
                    </p>

                    {{-- Comparison dengan bulan lalu --}}
                    <div class="bg-gray-100 rounded-lg px-3 py-2 mt-3 inline-flex items-center gap-1">
                        @if($stats['income']['direction'] == 'up')
                        <span class="text-green-600 text-sm">↑</span>
                        <span class="text-green-600 text-sm font-semibold">
                            {{ number_format($stats['income']['change'], 2) }}%
                        </span>
                        @else
                        <span class="text-red-600 text-sm">↓</span>
                        <span class="text-red-600 text-sm font-semibold">
                            {{ number_format($stats['income']['change'], 2) }}%
                        </span>
                        @endif
                        <span class="text-gray-500 text-sm">dari bulan lalu</span>
                    </div>
                </div>

                <img src="{{ asset('images/IconContainer.png') }}" 
                     class="w-14 h-14 object-contain ml-4"
                     onerror="this.style.display='none'">
            </div>
        </div>

        {{-- CARD 2: Total Expenses --}}
        <div class="bg-white shadow rounded-xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Pengeluaran</p>
                    <p class="font-semibold text-xl text-[#0A1A2A]">
                        Rp {{ number_format($stats['expense']['value'], 0, ',', '.') }}
                    </p>

                    <div class="bg-gray-100 rounded-lg px-3 py-2 mt-3 inline-flex items-center gap-1">
                        @if($stats['expense']['direction'] == 'up')
                        <span class="text-red-600 text-sm">↑</span>
                        <span class="text-red-600 text-sm font-semibold">
                            {{ number_format($stats['expense']['change'], 2) }}%
                        </span>
                        @else
                        <span class="text-green-600 text-sm">↓</span>
                        <span class="text-green-600 text-sm font-semibold">
                            {{ number_format($stats['expense']['change'], 2) }}%
                        </span>
                        @endif
                        <span class="text-gray-500 text-sm">dari bulan lalu</span>
                    </div>
                </div>

                <img src="{{ asset('images/IconContainer2.png') }}" 
                     class="w-14 h-14 object-contain ml-4"
                     onerror="this.style.display='none'">
            </div>
        </div>

        {{-- CARD 3: Net Profit --}}
        <div class="bg-white shadow rounded-xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Laba Bersih</p>
                    <p class="font-semibold text-xl {{ $stats['profit']['value'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($stats['profit']['value'], 0, ',', '.') }}
                    </p>

                    <div class="bg-gray-100 rounded-lg px-3 py-2 mt-3 inline-flex items-center gap-1">
                        @if($stats['profit']['direction'] == 'up')
                        <span class="text-green-600 text-sm">↑</span>
                        <span class="text-green-600 text-sm font-semibold">
                            {{ number_format($stats['profit']['change'], 2) }}%
                        </span>
                        @else
                        <span class="text-red-600 text-sm">↓</span>
                        <span class="text-red-600 text-sm font-semibold">
                            {{ number_format($stats['profit']['change'], 2) }}%
                        </span>
                        @endif
                        <span class="text-gray-500 text-sm">dari bulan lalu</span>
                    </div>
                </div>

                <img src="{{ asset('images/IconContainer3.png') }}" 
                     class="w-14 h-14 object-contain ml-4"
                     onerror="this.style.display='none'">
            </div>
        </div>

    </div>

    {{-- ========================= --}}
    {{-- TABLE BREAKDOWN PER BULAN --}}
    {{-- ========================= --}}
    <div class="bg-white rounded-2xl shadow p-4 overflow-x-auto border border-gray-200">

        <table class="w-full min-w-[1200px] text-sm border-collapse">

            {{-- HEADER BULAN --}}
            <thead>
                <tr>
                    <th class="px-4 py-3 font-semibold text-left sticky left-0 bg-white z-10">Kategori</th>
                    @foreach($monthlyData['months'] as $item)
                    <th class="px-4 py-3 font-semibold text-center">
                        {{ \Carbon\Carbon::create($item['year'], $item['month'], 1)->translatedFormat('M Y') }}
                    </th>
                    @endforeach
                    <th class="px-4 py-3 font-semibold text-center bg-gray-50">Total</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">

                {{-- ========================= --}}
                {{-- INCOME SECTION --}}
                {{-- ========================= --}}
                <tr>
                    <td colspan="{{ count($monthlyData['months']) + 2 }}" class="px-4 py-4 font-bold text-gray-900 text-lg bg-blue-50">
                        Pemasukan (Income)
                    </td>
                </tr>

                {{-- Pembayaran Treatment --}}
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 sticky left-0 bg-white">Pembayaran Treatment</td>
                    @foreach($monthlyData['months'] as $item)
                    <td class="px-4 py-3 text-center">
                        Rp {{ number_format($item['income'], 0, ',', '.') }}
                    </td>
                    @endforeach
                    <td class="px-4 py-3 text-center font-semibold bg-gray-50">
                        Rp {{ number_format($monthlyData['totals']['income'], 0, ',', '.') }}
                    </td>
                </tr>

                {{-- GROSS PROFIT --}}
                <tr class="border-b font-semibold bg-blue-100">
                    <td class="px-4 py-3 sticky left-0 bg-blue-100">Total Pemasukan</td>
                    @foreach($monthlyData['months'] as $item)
                    <td class="px-4 py-3 text-center">
                        Rp {{ number_format($item['income'], 0, ',', '.') }}
                    </td>
                    @endforeach
                    <td class="px-4 py-3 text-center bg-blue-200">
                        Rp {{ number_format($monthlyData['totals']['income'], 0, ',', '.') }}
                    </td>
                </tr>

                {{-- ========================= --}}
                {{-- EXPENSE SECTION --}}
                {{-- ========================= --}}
                <tr>
                    <td colspan="{{ count($monthlyData['months']) + 2 }}" class="px-4 py-4 font-bold text-gray-900 text-lg bg-red-50">
                        Pengeluaran (Expense)
                    </td>
                </tr>

                {{-- Pembelian Obat --}}
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 sticky left-0 bg-white">Pembelian Obat</td>
                    @foreach($monthlyData['months'] as $item)
                    <td class="px-4 py-3 text-center">
                        Rp {{ number_format($breakdown['expense']['pembelian_obat'] ?? 0, 0, ',', '.') }}
                    </td>
                    @endforeach
                    <td class="px-4 py-3 text-center font-semibold bg-gray-50">
                        Rp {{ number_format($breakdown['expense']['pembelian_obat'] ?? 0, 0, ',', '.') }}
                    </td>
                </tr>

                {{-- Pengeluaran Operasional --}}
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 sticky left-0 bg-white">Pengeluaran Operasional</td>
                    @foreach($monthlyData['months'] as $item)
                    <td class="px-4 py-3 text-center">
                        Rp {{ number_format($breakdown['expense']['operasional'] ?? 0, 0, ',', '.') }}
                    </td>
                    @endforeach
                    <td class="px-4 py-3 text-center font-semibold bg-gray-50">
                        Rp {{ number_format($breakdown['expense']['operasional'] ?? 0, 0, ',', '.') }}
                    </td>
                </tr>

                {{-- TOTAL EXPENSE --}}
                <tr class="border-b font-semibold bg-red-100">
                    <td class="px-4 py-3 sticky left-0 bg-red-100">Total Pengeluaran</td>
                    @foreach($monthlyData['months'] as $item)
                    <td class="px-4 py-3 text-center">
                        Rp {{ number_format($item['expense'], 0, ',', '.') }}
                    </td>
                    @endforeach
                    <td class="px-4 py-3 text-center bg-red-200">
                        Rp {{ number_format($monthlyData['totals']['expense'], 0, ',', '.') }}
                    </td>
                </tr>

                {{-- ========================= --}}
                {{-- NET INCOME --}}
                {{-- ========================= --}}
                <tr class="font-bold bg-gradient-to-r from-yellow-50 to-yellow-100">
                    <td class="px-4 py-4 sticky left-0 bg-yellow-100">Laba Bersih (Net Profit)</td>
                    @foreach($monthlyData['months'] as $item)
                    <td class="px-4 py-4 text-center {{ $item['profit'] >= 0 ? 'text-green-700' : 'text-red-700' }}">
                        Rp {{ number_format($item['profit'], 0, ',', '.') }}
                    </td>
                    @endforeach
                    <td class="px-4 py-4 text-center bg-yellow-200 {{ $monthlyData['totals']['profit'] >= 0 ? 'text-green-700' : 'text-red-700' }}">
                        Rp {{ number_format($monthlyData['totals']['profit'], 0, ',', '.') }}
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    {{-- ========================= --}}
    {{-- BREAKDOWN DETAIL SECTION --}}
    {{-- ========================= --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Detail Pemasukan --}}
        <div class="bg-white rounded-xl shadow p-5 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <iconify-icon icon="mdi:cash-multiple" class="text-green-600 text-2xl mr-2"></iconify-icon>
                Detail Pemasukan - {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F Y') }}
            </h3>
            
            <div class="space-y-3">
                @if(isset($breakdown['income']['by_method']))
                    @foreach($breakdown['income']['by_method'] as $method => $total)
                    <div class="flex justify-between items-center py-2 border-b">
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ ucfirst($method) }}</p>
                            <p class="text-xs text-gray-500">{{ $breakdown['income']['count'] }} transaksi</p>
                        </div>
                        <span class="font-semibold text-green-600">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                    @endforeach
                @else
                    <p class="text-sm text-gray-500">Tidak ada data pemasukan</p>
                @endif
            </div>
        </div>

        {{-- Detail Pengeluaran --}}
        <div class="bg-white rounded-xl shadow p-5 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <iconify-icon icon="mdi:cash-minus" class="text-red-600 text-2xl mr-2"></iconify-icon>
                Detail Pengeluaran - {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F Y') }}
            </h3>
            
            <div class="space-y-3">
                @if(isset($breakdown['expense']['by_method']))
                    @foreach($breakdown['expense']['by_method'] as $method => $total)
                    <div class="flex justify-between items-center py-2 border-b">
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ ucfirst($method) }}</p>
                            <p class="text-xs text-gray-500">{{ $breakdown['expense']['count'] }} transaksi</p>
                        </div>
                        <span class="font-semibold text-red-600">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                    @endforeach
                @else
                    <p class="text-sm text-gray-500">Tidak ada data pengeluaran</p>
                @endif
            </div>
        </div>

    </div>

</div>

<style>
/* Sticky column styling */
.sticky {
    position: sticky;
    left: 0;
    z-index: 5;
}

/* Shadow untuk sticky column */
.sticky::after {
    content: '';
    position: absolute;
    top: 0;
    right: -4px;
    bottom: 0;
    width: 4px;
    background: linear-gradient(to right, rgba(0,0,0,0.1), transparent);
}
</style>

@endsection