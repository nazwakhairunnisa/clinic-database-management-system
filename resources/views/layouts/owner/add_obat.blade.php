@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Obat')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6 sm:p-8 transition-all duration-300">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-black">Add Obat</h2>
            <a href="{{ route('owner.obat') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </a>
        </div>

        <form class="space-y-5">
            {{-- Baris 1 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Name <span class="text-red-500">*</span></label>
                    <input type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Harga <span class="text-red-500">*</span></label>
                    <input type="number"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>
            </div>

            {{-- Baris 2 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Supplier <span class="text-red-500">*</span></label>
                    <input type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Status <span class="text-red-500">*</span></label>
                    <select
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                        <option value="">- select -</option>
                        <option>Sudah Dibayar</option>
                        <option>Belum Dibayar</option>
                        <option>Dibayar DP</option>
                    </select>
                </div>
            </div>

             {{-- Baris 3 --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="text-sm font-medium text-gray-600">Tanggal Jatuh Tempo</label>
        <input type="date" value="2025-10-25"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
    </div>
</div>

{{-- Submit --}}
<div class="pt-2">
    <button type="submit" 
        class="bg-[#2F9CCA] text-white px-6 py-2 rounded-lg hover:bg-[#1788b4] transition-all duration-200">
        Add
    </button>
</div>

        </form>
    </div>
</div>
@endsection

