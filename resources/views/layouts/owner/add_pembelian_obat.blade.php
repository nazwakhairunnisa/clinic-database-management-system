@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Pembelian Obat')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6 sm:p-8 transition-all duration-300">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-black">Add Pembelian Obat</h2>

            <a href="{{ route('owner.pembelian_obat') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </a>
        </div>

        {{-- FORM --}}
        <form class="space-y-5">

            {{-- ROW 1: Nama Obat + Tanggal Pembelian --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Nama Obat --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">Nama Obat <span class="text-red-500">*</span></label>
                    <input type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                        focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>

                {{-- Tanggal Pembelian --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">Tanggal Pembelian <span class="text-red-500">*</span></label>
                    <input type="date"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                        focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>

            </div>


            {{-- ROW 2: Supplier + Jumlah --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Supplier --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">Supplier <span class="text-red-500">*</span></label>
                    <input type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                        focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" min="1"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                        focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>

            </div>


            {{-- ROW 3: Harga Satuan + Status --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Harga Satuan --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">Harga Satuan <span class="text-red-500">*</span></label>
                    <input type="number" min="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                        focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>

                {{-- Status Pembayaran --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">Status Pembayaran <span class="text-red-500">*</span></label>
                    <select
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                        focus:ring-2 focus:ring-[#EED892] outline-none">
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
