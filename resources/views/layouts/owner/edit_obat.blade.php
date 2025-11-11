@extends('layouts.owner.app')

@section('pageTitle', 'Edit Obat')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-gray-100 min-h-screen flex justify-center items-start pt-8 sm:pt-12">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6 sm:p-8 transition-all duration-300">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-[#806B3F]">Edit Obat</h2>
            <a href="{{ route('owner.obat') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </a>
        </div>

        <form class="space-y-5">
            {{-- Baris 1 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Name <span class="text-red-500">*</span></label>
                    <input type="text" value="Vitamin E"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Harga <span class="text-red-500">*</span></label>
                    <input type="number" value="500000"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>
            </div>

            {{-- Baris 2 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Supplier <span class="text-red-500">*</span></label>
                    <input type="text" value="Shopee"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Status <span class="text-red-500">*</span></label>
                    <select
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                        <option>Sudah Dibayar</option>
                        <option selected>Belum Dibayar</option>
                        <option>Dibayar DP</option>
                    </select>
                </div>
            </div>

            {{-- Baris 3 --}}
            <div>
                <label class="text-sm font-medium text-gray-600">Tanggal Jatuh Tempo</label>
                <input type="date" value="2025-10-25"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
            </div>

            {{-- Tombol Submit --}}
            <div class="pt-2">
                <button type="submit"
                    class="bg-[#806B3F] text-white px-6 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-200">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
