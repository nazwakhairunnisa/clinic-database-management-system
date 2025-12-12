@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Pembelian Obat')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif]">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">
        <div class="flex w-full items-center justify-between gap-3">
            
            {{-- Search Bar --}}
            <form method="GET" action="{{ route('owner.pembelian_obat.index') }}" class="relative flex-grow flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Obat / Supplier"
                    class="border border-white rounded-full pl-10 pr-4 py-2 w-full sm:w-60 focus:ring-2 focus:ring-[#EED892] focus:outline-none text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
                
                {{-- Filter Status --}}
                <select name="status" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-full px-6 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                    <option value="">Semua Status</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Sudah Dibayar</option>
                    <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Dibayar</option>
                </select>
            </form>

            {{-- Buttons --}}
            <div class="flex space-x-2 sm:space-x-3 shrink-0">
                {{-- Export --}}
                <a href="{{ route('owner.pembelian_obat.export') . '?' . http_build_query(request()->only(['search', 'status'])) }}"
                class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-file-export text-base sm:mr-2"></i>
                    <span class="hidden sm:inline">Export</span>
                </a>

                {{-- Add Pembelian --}}
                <a href="{{ route('owner.pembelian_obat.add') }}"
                    class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-plus text-base sm:mr-2"></i>
                    <span class="hidden sm:inline">Add Pembelian</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center">
        <i class="fa-solid fa-circle-check mr-2"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center">
        <i class="fa-solid fa-circle-xmark mr-2"></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto">
        <table class="min-w-[1000px] w-full text-sm text-left border-collapse" id="pembelianObatTable">
            <thead>
                <tr class="bg-gray-100/60 text-gray-700 font-light">
                    <th class="px-4 py-3 border-b border-gray-300 font-light rounded-l-lg">ID</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Nama Obat</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Tgl Beli</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Supplier</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Jumlah</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Harga Satuan</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Total</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Status</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Jatuh Tempo</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light rounded-r-lg">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @forelse($pembelian as $item)
                <tr class="bg-white hover:bg-gray-50 border-b border-gray-200 transition">
                    <td class="px-4 py-3">{{ $item->id_pembelian_obat }}</td>
                    <td class="px-4 py-3 font-medium">{{ $item->obat->nama_obat ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $item->tanggal_beli->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $item->supplier->nama_supplier ?? '-' }}</td>
                    <td class="px-4 py-3">{{ number_format($item->jumlah, 0, ',', '.') }} {{ $item->obat->satuan ?? '' }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 font-semibold">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>

                    {{-- STATUS BADGE --}}
                    <td class="px-4 py-3">
                        @php
                            $statusClass = match($item->status_pembayaran) {
                                'lunas' => 'bg-green-100 text-green-700',
                                'belum' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700'
                            };
                        @endphp
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                            {{ $item->status_text }}
                        </span>
                    </td>

                    <td class="px-4 py-3">
                        @if($item->tanggal_jatuh_tempo)
                            {{ $item->tanggal_jatuh_tempo->format('d/m/Y') }}
                            @if($item->status_pembayaran == 'belum' && $item->tanggal_jatuh_tempo->isPast())
                                <span class="text-red-500 text-xs block">(Terlambat)</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>

                    {{-- Action --}}
                    <td class="px-4 py-3 flex space-x-3">
                        {{-- EDIT --}}
                        <a href="{{ route('owner.pembelian_obat.edit', $item->id_pembelian_obat) }}"
                            class="text-gray-700 hover:text-blue-600 transition-all duration-200"
                            title="Edit">
                            <i class="fa-solid fa-pen text-lg"></i>
                        </a>

                        {{-- DELETE --}}
                        <form action="{{ route('owner.pembelian_obat.destroy', $item->id_pembelian_obat) }}" 
                              method="POST"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pembelian ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-gray-700 hover:text-red-600 transition-all duration-200"
                                title="Delete">
                                <i class="fa-solid fa-trash text-lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-inbox text-4xl mb-2"></i>
                        <p>Tidak ada data pembelian obat</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $pembelian->links() }}
        </div>
    </div>

</div>

@endsection