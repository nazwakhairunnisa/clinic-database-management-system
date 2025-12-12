@extends('layouts.owner.app')

@section('pageTitle', 'Edit Supplier')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-2xl p-6 sm:p-8 transition-all duration-300">
        
        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-black">Edit Supplier</h2>
            <a href="{{ route('owner.supplier') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </a>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- FORM --}}
        <form action="{{ route('owner.supplier.update', $supplier->id_supplier) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Supplier --}}
            <div>
                <label class="text-sm font-medium text-gray-600">
                    Nama Supplier <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_supplier" value="{{ old('nama_supplier', $supplier->nama_supplier) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                @error('nama_supplier')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nomor Supplier --}}
            <div>
                <label class="text-sm font-medium text-gray-600">
                    Nomor Telepon <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nomor_supplier" value="{{ old('nomor_supplier', $supplier->nomor_supplier) }}" required
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

            {{-- Info Pembelian --}}
            @if($supplier->pembelianObat()->count() > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fa-solid fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                    <div class="text-sm text-yellow-700">
                        <p class="font-medium mb-1">Perhatian:</p>
                        <p>Supplier ini memiliki <strong>{{ $supplier->pembelianObat()->count() }} transaksi pembelian</strong>. Perubahan data tidak akan mempengaruhi transaksi yang sudah ada.</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Submit Buttons --}}
            <div class="pt-2 flex space-x-3">
                <button type="submit" 
                    class="bg-[#2F9CCA] text-white px-6 py-2 rounded-lg hover:bg-[#1788b4] transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-save mr-2"></i>
                    Update
                </button>
                <a href="{{ route('owner.supplier') }}"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-times mr-2"></i>
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>
@endsection