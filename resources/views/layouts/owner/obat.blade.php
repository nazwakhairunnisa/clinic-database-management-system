@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Obat')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif]">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">

        {{-- Search + Buttons sejajar --}}
        <div class="flex w-full items-center justify-between gap-3">
            
            {{-- Search Bar --}}
            <div class="relative flex-grow">
                <input type="text" placeholder="Search Obat"
                    class="border border-white rounded-full pl-10 pr-4 py-2 w-full sm:w-60 focus:ring-2 focus:ring-[#EED892] focus:outline-none text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
            </div>

            {{-- Buttons di kanan --}}
            <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                {{-- Export --}}
                <button
                    class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-file-export text-base sm:mr-2"></i>
                    <span class="hidden sm:inline">Export</span>
                </button>

                {{-- Add Obat --}}
        
            <a href="{{ route('owner.obat.add') }}"
                class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                <i class="fa-solid fa-plus text-base sm:mr-2"></i>
                <span class="hidden sm:inline">Add Obat</span>
            </a>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto transition-all duration-300">
        <table class="min-w-[800px] w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100/60 text-gray-700 font-normal">
                    <th class="px-4 py-3 rounded-l-lg border-b border-gray-300">No</th>
                    <th class="px-4 py-3 border-b border-gray-300">Name</th>
                    <th class="px-4 py-3 border-b border-gray-300">Harga</th>
                    <th class="px-4 py-3 border-b border-gray-300">Supplier</th>
                    <th class="px-4 py-3 border-b border-gray-300">Status</th>
                    <th class="px-4 py-3 border-b border-gray-300">Jatuh Tempo</th>
                    <th class="px-4 py-3 rounded-r-lg border-b border-gray-300">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @foreach([
                    ['no'=>1,'name'=>'Vitamin E','harga'=>'Rp 500.000','supplier'=>'Shopee','status'=>'Sudah Dibayar','tempo'=>'-'],
                    ['no'=>2,'name'=>'Vitamin C','harga'=>'Rp 350.000','supplier'=>'Tokopedia','status'=>'Belum Dibayar','tempo'=>'25 Oktober 2025'],
                    ['no'=>3,'name'=>'Vitamin D','harga'=>'Rp 300.000','supplier'=>'Orang','status'=>'Dibayar DP','tempo'=>'26 Oktober 2025'],
                    ['no'=>4,'name'=>'Vitamin E','harga'=>'Rp 340.000','supplier'=>'Orang Lain','status'=>'Belum Dibayar','tempo'=>'29 Oktober 2025'],
                ] as $row)
                <tr class="bg-white hover:bg-gray-50 transition-all duration-200 border-b border-gray-200">
                    <td class="px-4 py-3">{{ $row['no'] }}</td>
                    <td class="px-4 py-3">{{ $row['name'] }}</td>
                    <td class="px-4 py-3">{{ $row['harga'] }}</td>
                    <td class="px-4 py-3">{{ $row['supplier'] }}</td>
                    <td class="px-4 py-3">
                        @if ($row['status'] === 'Sudah Dibayar')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Sudah Dibayar</span>
                        @elseif ($row['status'] === 'Belum Dibayar')
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">Belum Dibayar</span>
                        @elseif ($row['status'] === 'Dibayar DP')
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">Dibayar DP</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $row['tempo'] }}</td>

                    {{-- Action --}}
                    <td class="px-4 py-3 flex space-x-3">
                        {{-- Edit --}}
                        <td class="px-4 py-3 flex space-x-3">
    <a href="{{ route('owner.obat.edit') }}" class="text-gray-700 hover:text-black transition-all duration-200">
        <i class="fa-solid fa-pen"></i>
    </a>

    {{-- Delete --}}
    <button class="text-gray-700 hover:text-black transition-all duration-200"
        onclick="confirm('Apakah kamu yakin ingin menghapus data obat ini?')">
        <i class="fa-solid fa-trash"></i>
    </button>
</td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
