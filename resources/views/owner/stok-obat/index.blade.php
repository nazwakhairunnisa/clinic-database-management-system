@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Stok Obat')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif]">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">
        <div class="flex w-full items-center justify-between gap-3">
            
            {{-- Search Bar --}}
            <form method="GET" action="{{ route('owner.stok-obat.index') }}" class="relative flex-grow">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Obat"
                    class="border border-white rounded-full pl-10 pr-4 py-2 w-full sm:w-60 focus:ring-2 focus:ring-[#EED892] focus:outline-none text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
            </form>

            {{-- Buttons --}}
            <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                {{-- Export PDF --}}
                <a href="{{ route('owner.stok-obat.export') . '?' . http_build_query(request()->query()) }}"
                class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-file-export text-base sm:mr-2"></i>
                    <span class="hidden sm:inline">Export</span>
                </a>

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
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto transition-all duration-300">
        <table class="min-w-[800px] w-full text-sm text-left border-collapse" id="stokObatTable">
            <thead>
                <tr class="bg-gray-100/60 text-gray-700 font-normal">
                    <th class="px-4 py-3 rounded-l-lg border-b border-gray-300">ID</th>
                    <th class="px-4 py-3 border-b border-gray-300">Nama Obat</th>
                    <th class="px-4 py-3 border-b border-gray-300">Satuan</th>
                    <th class="px-4 py-3 border-b border-gray-300">Stok Awal</th>
                    <th class="px-4 py-3 border-b border-gray-300">Stok Terkini</th>
                    <th class="px-4 py-3 border-b border-gray-300">Status Stok</th>
                    <th class="px-4 py-3 border-b border-gray-300">Last Update</th>
                    <th class="px-4 py-3 rounded-r-lg border-b border-gray-300">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @forelse($stokObat as $item)
                <tr class="bg-white hover:bg-gray-50 transition-all duration-200 border-b border-gray-200">
                    <td class="px-4 py-3">{{ $item->id_obat }}</td>
                    <td class="px-4 py-3 font-medium">{{ $item->nama_obat }}</td>
                    <td class="px-4 py-3">{{ $item->satuan }}</td>
                    <td class="px-4 py-3">{{ number_format($item->stok_awal, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <span class="font-semibold {{ $item->stok_terkini <= 10 ? 'text-red-600' : '' }}">
                            {{ number_format($item->stok_terkini, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $statusColors = [
                                'Habis' => 'bg-red-100 text-red-700',
                                'Sedikit Lagi' => 'bg-orange-100 text-orange-700',
                                'Menipis' => 'bg-yellow-100 text-yellow-700',
                                'Aman' => 'bg-green-100 text-green-700',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$item->status_stok] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $item->status_stok }}
                        </span>
                    </td>
                    <td class="px-4 py-3">{{ $item->tanggal_update->format('d/m/Y') }}</td>

                    {{-- Action --}}
                    <td class="px-4 py-3 flex space-x-3">
                        <a href="{{ route('owner.stok-obat.edit', $item->id_obat) }}" 
                           class="text-gray-700 hover:text-blue-600 transition-all duration-200"
                           title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <form action="{{ route('owner.stok-obat.destroy', $item->id_obat) }}" 
                              method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus obat {{ $item->nama_obat }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-700 hover:text-red-600 transition-all duration-200"
                                    title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-inbox text-4xl mb-2"></i>
                        <p>Tidak ada data stok obat</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $stokObat->links() }}
        </div>
    </div>
</div>


@endsection