@extends('layouts.owner.app')

@section('pageTitle', 'Pengeluaran')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif]">

    {{-- Alert Messages --}}
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

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">

        {{-- Search + Export + Add --}}
        <div class="flex w-full items-center justify-between gap-3">

            {{-- Search --}}
            <form method="GET" action="{{ route('owner.pengeluaran') }}" class="relative flex-grow">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Pengeluaran"
                    class="border border-white rounded-full pl-10 pr-4 py-2 w-full sm:w-60 
                           focus:ring-2 focus:ring-[#EED892] focus:outline-none text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
            </form>

            {{-- Export --}}
            <a href="{{ route('owner.pengeluaran.export') }}"
                class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-full
                       hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                <iconify-icon icon="bx:export" class="text-xl sm:mr-2"></iconify-icon>
                <span class="hidden sm:inline">Export</span>
            </a>

            {{-- Add Pengeluaran --}}
            <a href="{{ route('owner.pengeluaran.add') }}"
                class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-full
                       hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                <iconify-icon icon="material-symbols:add-rounded" class="text-xl sm:mr-2"></iconify-icon>
                <span class="hidden sm:inline">Add Expenses</span>
            </a>

        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto transition-all duration-300">

        <table class="min-w-[800px] w-full text-sm text-left border-collapse">

            <thead>
                <tr class="bg-gray-100/60 text-gray-700 font-light">
                    <th class="px-4 py-3 rounded-l-lg border-b border-gray-300 font-light">No</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Name</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Harga</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Date</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Payment Method</th>
                    <th class="px-4 py-3 rounded-r-lg border-b border-gray-300 font-light">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @forelse($pengeluaran as $index => $row)
                <tr class="bg-white hover:bg-gray-50 transition-all duration-200 border-b border-gray-200">
                    <td class="px-4 py-3">{{ $pengeluaran->firstItem() + $index }}</td>
                    <td class="px-4 py-3">{{ $row->nama_transaksi }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($row->jumlah, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">{{ $row->tanggal_transaksi->format('d F Y') }}</td>
                    <td class="px-4 py-3">
                        @if($row->metode_pembayaran == 'cash')
                            Cash
                        @elseif($row->metode_pembayaran == 'transfer')
                            Transfer
                        @else
                            E-Wallet
                        @endif
                    </td>

                    {{-- ACTION --}}
                    <td class="px-4 py-3 flex space-x-3">
                        @if(!$row->id_pembelian_obat)
                            <a href="{{ route('owner.pengeluaran.edit', $row->id_transaksi) }}" 
                                class="text-gray-700 hover:text-black transition-all">
                                <iconify-icon icon="mingcute:edit-line" class="text-lg"></iconify-icon>
                            </a>

                            {{-- DELETE --}}
                            <form action="{{ route('owner.pengeluaran.destroy', $row->id_transaksi) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Apakah kamu yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-700 hover:text-red-600 transition-all duration-200">
                                    <iconify-icon icon="material-symbols:delete-outline" class="text-lg"></iconify-icon>
                                </button>
                            </form>
                        @else
                            <span class="text-gray-400 text-xs">Dari Pembelian Obat</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                        Tidak ada data pengeluaran
                    </td>
                </tr>

                @endforelse
            </tbody>

        </table>
    
        {{-- Pagination --}}
        <div class="mt-4">
            {{ $pengeluaran->links() }}
        </div>
    </div>

</div>

@endsection
