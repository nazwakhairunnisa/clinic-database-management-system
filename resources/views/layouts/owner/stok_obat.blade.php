@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Stok Obat')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif]">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">

        {{-- Search + Buttons --}}
        <div class="flex w-full items-center justify-between gap-3">
            
            {{-- Search Bar --}}
            <div class="relative flex-grow">
                <input type="text" placeholder="Search Obat" id="searchInput"
                    class="border border-white rounded-full pl-10 pr-4 py-2 w-full sm:w-60 focus:ring-2 focus:ring-[#EED892] focus:outline-none text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                {{-- Export --}}
                <button
                    class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-file-export text-base sm:mr-2"></i>
                    <span class="hidden sm:inline">Export</span>
                </button>

                {{-- Add Obat --}}
                <a href="{{ route('owner.stok-obat.add') }}"
                    class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-plus text-base sm:mr-2"></i>
                    <span class="hidden sm:inline">Add Obat</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto transition-all duration-300">
        <table class="min-w-[800px] w-full text-sm text-left border-collapse" id="stokObatTable">
            <thead>
                <tr class="bg-gray-100/60 text-gray-700 font-normal">
                    <th class="px-4 py-3 rounded-l-lg border-b border-gray-300">ID</th>
                    <th class="px-4 py-3 border-b border-gray-300">Name</th>
                    <th class="px-4 py-3 border-b border-gray-300">Harga</th>
                    <th class="px-4 py-3 border-b border-gray-300">Supplier</th>
                    <th class="px-4 py-3 border-b border-gray-300">Status</th>
                    <th class="px-4 py-3 border-b border-gray-300">Jatuh Tempo</th>
                    <th class="px-4 py-3 rounded-r-lg border-b border-gray-300">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @forelse($stokObat as $item)
                <tr class="bg-white hover:bg-gray-50 transition-all duration-200 border-b border-gray-200">
                    <td class="px-4 py-3">{{ $item->id_obat }}</td>
                    <td class="px-4 py-3">{{ $item->nama_obat }}</td>
                    <td class="px-4 py-3">
                        @if($item->pembelian()->exists())
                            Rp {{ number_format($item->pembelian()->latest()->first()->harga_satuan ?? 0, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($item->pembelian()->exists())
                            {{ $item->pembelian()->latest()->first()->supplier->nama_supplier ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $latestPembelian = $item->pembelian()->latest()->first();
                        @endphp
                        
                        @if($latestPembelian)
                            @if($latestPembelian->status_pembayaran === 'lunas')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Sudah Dibayar</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">Belum Dibayar</span>
                            @endif
                        @else
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($latestPembelian && $latestPembelian->tanggal_jatuh_tempo)
                            {{ $latestPembelian->tanggal_jatuh_tempo->format('d F Y') }}
                        @else
                            -
                        @endif
                    </td>

                    {{-- Action --}}
                    <td class="px-4 py-3 flex space-x-3">
                        <a href="{{ route('owner.stok-obat.edit', $item->id_obat) }}" 
                           class="text-gray-700 hover:text-black transition-all duration-200">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <form action="{{ route('owner.stok-obat.destroy', $item->id_obat) }}" 
                              method="POST" 
                              onsubmit="return confirm('Apakah kamu yakin ingin menghapus obat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-700 hover:text-black transition-all duration-200">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada data stok obat</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Simple Search Script --}}
<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#stokObatTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});
</script>
@endsection