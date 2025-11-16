@extends('layouts.owner.app') 

@section('pageTitle', 'Pengeluaran')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

@php
    $data = [
        'name' => '',
        'harga' => '',
        'date' => '',
        'payment' => '',
    ];
@endphp

<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6 sm:p-8 transition-all duration-300 relative">

        {{-- Tombol X --}}
        <a href="{{ route('owner.pengeluaran') }}" 
           class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 transition">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </a>

        {{-- Title --}}
        <h2 class="text-xl sm:text-2xl font-semibold text-black mb-8">
            Edit Pengeluaran
        </h2>

        {{-- FORM --}}
        <form class="space-y-6">

            {{-- ROW 1: NAME + HARGA --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="text-sm font-medium text-gray-600">Name <span class="text-red-500">*</span></label>
                    <input type="text" value="{{ $data['name'] }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                               focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" value="{{ $data['harga'] }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                               focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>

            </div>

            {{-- ROW 2: DATE + PAYMENT METHOD --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="text-sm font-medium text-gray-600">Date <span class="text-red-500">*</span></label>
                    <input type="date" value="{{ $data['date'] }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                               focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>

                {{-- Payment Method --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Payment Method <span class="text-red-500">*</span>
                    </label>
                    <select 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                               focus:ring-2 focus:ring-[#EED892] outline-none">
                        <option value="" selected disabled>Pilih metode pembayaran</option>
                        <option>Cash</option>
                        <option>Debit</option>
                        <option>QRIS</option>
                    </select>
                </div>
            </div>

            {{-- BUTTON SAVE --}}
            <div class="pt-2">
                <button type="submit"
                    class="bg-[#2F9CCA] text-white px-6 py-2 rounded-lg hover:bg-[#1788b4] transition">
                    Save
                </button>
            </div>

        </form>
    </div>
</div>

@endsection
