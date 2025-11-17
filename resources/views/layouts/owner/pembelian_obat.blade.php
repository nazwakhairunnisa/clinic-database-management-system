@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Pembelian Obat')

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

    
                {{-- Buttons --}}
            <div class="flex space-x-2 sm:space-x-3 shrink-0">

                {{-- Export --}}
                <button
                    class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-full
                    hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    
                    <iconify-icon icon="bx:export" class="text-xl sm:mr-2"></iconify-icon>
                    <span class="hidden sm:inline">Export</span>
                </button>

                {{-- Add Patient --}}
                <a href="{{ route('owner.pembelian_obat.add') }}"     
                    class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-full
                    hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    
                    <iconify-icon icon="material-symbols:add-rounded" class="text-xl sm:mr-2"></iconify-icon>
                    <span class="hidden sm:inline">Add Obat</span>
                </a>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto">

        <table class="min-w-[1000px] w-full text-sm text-left border-collapse">

            <thead>
                <tr class="bg-gray-100/60 text-gray-700 font-light">
                    <th class="px-4 py-3 border-b border-gray-300 font-light rounded-l-lg">ID</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Nama Obat</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Tgl Beli</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Supplier</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Jumlah</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Harga Satuan</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Status</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Jatuh Tempo</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light rounded-r-lg">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">

                @foreach([
                    ['id'=>1,'nama'=>'Vitamin E','tgl'=>'23/01/2024','supplier'=>'Shopee','jumlah'=>1,'harga'=>'Rp 350.000','status'=>'Sudah Dibayar','color'=>'green','tempo'=>'-'],
                    ['id'=>2,'nama'=>'Vitamin C','tgl'=>'23/01/2024','supplier'=>'Tokopedia','jumlah'=>1,'harga'=>'Rp 350.000','status'=>'Belum Dibayar','color'=>'red','tempo'=>'25 Oktober 2025'],
                    ['id'=>3,'nama'=>'Vitamin E','tgl'=>'23/01/2024','supplier'=>'Orang','jumlah'=>1,'harga'=>'Rp 350.000','status'=>'Dibayar DP','color'=>'yellow','tempo'=>'25 Oktober 2025'],
                    ['id'=>4,'nama'=>'Vitamin E','tgl'=>'23/01/2024','supplier'=>'Orang lah','jumlah'=>1,'harga'=>'Rp 350.000','status'=>'Belum Dibayar','color'=>'red','tempo'=>'25 Oktober 2025'],
                ] as $row)

                <tr class="bg-white hover:bg-gray-50 border-b border-gray-200 transition">
                    <td class="px-4 py-3">{{ $row['id'] }}.</td>
                    <td class="px-4 py-3">{{ $row['nama'] }}</td>
                    <td class="px-4 py-3">{{ $row['tgl'] }}</td>
                    <td class="px-4 py-3">{{ $row['supplier'] }}</td>
                    <td class="px-4 py-3">{{ $row['jumlah'] }}</td>
                    <td class="px-4 py-3">{{ $row['harga'] }}</td>

                    {{-- STATUS BADGE --}}
                    <td class="px-4 py-3">
                        <span class="
                            px-3 py-1 text-xs font-semibold rounded-full
                            {{ $row['color']=='green' ? 'bg-green-200 text-green-700' : '' }}
                            {{ $row['color']=='red' ? 'bg-red-200 text-red-700' : '' }}
                            {{ $row['color']=='yellow' ? 'bg-yellow-200 text-yellow-700' : '' }}">
                            {{ $row['status'] }}
                        </span>
                    </td>

                    <td class="px-4 py-3">{{ $row['tempo'] }}</td>

                    {{-- Action --}}
<td class="px-4 py-3 flex space-x-3">

    {{-- EDIT --}}
    <a href="{{ route('owner.pembelian_obat.edit') }}"
        class="text-gray-700 hover:text-black transition-all duration-200">
        <iconify-icon icon="mingcute:edit-line" class="text-lg"></iconify-icon>
    </a>

    {{-- DELETE --}}
    <button 
        class="text-gray-700 hover:text-red-600 transition-all duration-200"
        onclick="return confirm('Apakah kamu yakin ingin menghapus data ini?')">
        <iconify-icon icon="material-symbols:delete-outline" class="text-lg"></iconify-icon>
    </button>

</td>

                </tr>

                @endforeach

            </tbody>
        </table>

    </div>

</div>
@endsection
