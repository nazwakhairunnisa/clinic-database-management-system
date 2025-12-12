@extends('layouts.owner.app')

@section('pageTitle', 'Tambah Supplier')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-2xl p-6 sm:p-8 transition-all duration-300">
        
        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-black">Tambah Supplier</h2>
            <a href="{{ route('owner.supplier.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </a>
        </div>

        {{-- ERROR MESSAGES --}}
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat error pada form:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- FORM --}}
        <form action="{{ route('owner.supplier.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Nama Supplier --}}
            <div>
                <label class="text-sm font-medium text-gray-600">
                    Nama Supplier <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_supplier" value="{{ old('nama_supplier') }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none"
                    placeholder="Contoh: PT. Kimia Farma">
                @error('nama_supplier')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nomor Supplier --}}
            <div>
                <label class="text-sm font-medium text-gray-600">
                    Nomor Telepon <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nomor_supplier" value="{{ old('nomor_supplier') }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none"
                    placeholder="Contoh: 081234567890 atau +6281234567890">
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fa-solid fa-info-circle"></i>
                    Format: 08xxxxxxxxxx atau +628xxxxxxxxxx
                </p>
                @error('nomor_supplier')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Buttons --}}
            <div class="pt-2 flex space-x-3">
                <button type="submit" 
                    class="bg-[#2F9CCA] text-white px-6 py-2 rounded-lg hover:bg-[#1788b4] transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-save mr-2"></i>
                    Simpan
                </button>
                <a href="{{ route('owner.supplier.index') }}"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-times mr-2"></i>
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>
@endsection