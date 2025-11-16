@extends('layouts.owner.app')

@section('pageTitle', 'Pengeluaran')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif]">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">

        {{-- Search + Export + Add --}}
        <div class="flex w-full items-center justify-between gap-3">

            {{-- Search --}}
            <div class="relative flex-grow">
                <input type="text" placeholder="Search Pengeluaran"
                    class="border border-white rounded-full pl-10 pr-4 py-2 w-full sm:w-60 
                           focus:ring-2 focus:ring-[#EED892] focus:outline-none text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
            </div>

            {{-- Export --}}
            <button
                class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-full
                       hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                <iconify-icon icon="bx:export" class="text-xl sm:mr-2"></iconify-icon>
                <span class="hidden sm:inline">Export</span>
            </button>

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
                @foreach([
                    ['no'=>1,'name'=>'Alat 1','harga'=>'Rp 350.000','date'=>'25 Oktober 2025','payment'=>'Cash'],
                    ['no'=>2,'name'=>'Alat 2','harga'=>'Rp 350.000','date'=>'25 Oktober 2025','payment'=>'Debit'],
                    ['no'=>3,'name'=>'Obat 1','harga'=>'Rp 350.000','date'=>'26 Oktober 2025','payment'=>'QRIS'],
                    ['no'=>4,'name'=>'Obat 2','harga'=>'Rp 350.000','date'=>'25 Oktober 2025','payment'=>'Cash'],
                ] as $row)

                <tr class="bg-white hover:bg-gray-50 transition-all duration-200 border-b border-gray-200">
                    <td class="px-4 py-3">{{ $row['no'] }}</td>
                    <td class="px-4 py-3">{{ $row['name'] }}</td>
                    <td class="px-4 py-3">{{ $row['harga'] }}</td>
                    <td class="px-4 py-3">{{ $row['date'] }}</td>
                    <td class="px-4 py-3">{{ $row['payment'] }}</td>

                     {{-- ACTION --}}
                    <td class="px-4 py-3 flex space-x-3">
                        <a href="{{ route('owner.pengeluaran.edit') }}" 
                            class="text-gray-700 hover:text-black transition-all">
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
