@extends('layouts.owner.app') 

@section('pageTitle', 'Edit Pendapatan')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6 sm:p-8 transition-all duration-300 relative">

        {{-- Tombol X --}}
        <a href="{{ route('owner.pendapatan.index') }}" 
           class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 transition">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </a>

        {{-- TITLE --}}
        <h2 class="text-xl sm:text-2xl font-semibold text-black mb-8">
            Edit Pendapatan
        </h2>

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
        <form action="{{ route('owner.pendapatan.update', $pendapatan->id_transaksi) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- ROW 1: NAME + JUMLAH --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- nama --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        name="nama_transaksi"
                        value="{{ old('nama_transaksi', $pendapatan->nama_transaksi) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                               focus:ring-2 focus:ring-[#EED892] outline-none @error('nama_transaksi') border-red-500 @enderror">
                    @error('nama_transaksi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Jumlah (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                        name="jumlah"
                        value="{{ old('jumlah', $pendapatan->jumlah) }}"
                        step="0.01"
                        min="0"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                               focus:ring-2 focus:ring-[#EED892] outline-none @error('jumlah') border-red-500 @enderror">
                    @error('jumlah')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ROW 2: DATE + PAYMENT METHOD --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Date --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                        name="tanggal_transaksi"
                        value="{{ old('tanggal_transaksi', $pendapatan->tanggal_transaksi->format('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                               focus:ring-2 focus:ring-[#EED892] outline-none @error('tanggal_transaksi') border-red-500 @enderror">
                    @error('tanggal_transaksi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Payment Method --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Payment Method <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="metode_pembayaran"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                               focus:ring-2 focus:ring-[#EED892] outline-none @error('metode_pembayaran') border-red-500 @enderror">
                        <option value="" disabled>Pilih metode pembayaran</option>
                        <option value="cash" {{ old('metode_pembayaran', $pendapatan->metode_pembayaran) == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="transfer" {{ old('metode_pembayaran', $pendapatan->metode_pembayaran) == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="ewallet" {{ old('metode_pembayaran', $pendapatan->metode_pembayaran) == 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                    </select>
                    @error('metode_pembayaran')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ROW 3: KETERANGAN --}}
            <div>
                <label class="text-sm font-medium text-gray-600">
                    Keterangan (Opsional)
                </label>
                <textarea 
                    name="keterangan"
                    rows="3"
                    placeholder="Tambahkan keterangan jika diperlukan"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                           focus:ring-2 focus:ring-[#EED892] outline-none">{{ old('keterangan', $pendapatan->keterangan) }}</textarea>
            </div>

            {{-- BUTTON SAVE --}}
            <div class="pt-2 flex gap-3">
                <button type="submit"
                    class="bg-[#2F9CCA] text-white px-6 py-2 rounded-lg hover:bg-[#1788b4] transition">
                    Save Changes
                </button>
                <a href="{{ route('owner.pendapatan.index') }}"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                    Cancel
                </a>
            </div>

        </form>

    </div>
</div>

@endsection