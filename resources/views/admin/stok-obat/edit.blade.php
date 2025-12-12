@extends('layouts.admin')

@section('pageTitle', 'Edit Stok Obat')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6 sm:p-8 transition-all duration-300">
        
        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-black">Edit Stok Obat</h2>
            <a href="{{ route('admin.stok-obat.index') }}" class="text-gray-400 hover:text-gray-600 transition">
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
        <form action="{{ route('admin.stok-obat.update', $obat->id_obat) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Row 1: Nama Obat --}}
            <div>
                <label class="text-sm font-medium text-gray-600">Nama Obat <span class="text-red-500">*</span></label>
                <input type="text" name="nama_obat" value="{{ old('nama_obat', $obat->nama_obat) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                @error('nama_obat')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Row 2: Satuan & Stok Info --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Satuan <span class="text-red-500">*</span></label>
                    <select name="satuan" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                        <option value="">- Pilih Satuan -</option>
                        <option value="pcs" {{ old('satuan', $obat->satuan) == 'pcs' ? 'selected' : '' }}>Pcs</option>
                        <option value="box" {{ old('satuan', $obat->satuan) == 'box' ? 'selected' : '' }}>Box</option>
                        <option value="botol" {{ old('satuan', $obat->satuan) == 'botol' ? 'selected' : '' }}>Botol</option>
                        <option value="strip" {{ old('satuan', $obat->satuan) == 'strip' ? 'selected' : '' }}>Strip</option>
                        <option value="tube" {{ old('satuan', $obat->satuan) == 'tube' ? 'selected' : '' }}>Tube</option>
                        <option value="vial" {{ old('satuan', $obat->satuan) == 'vial' ? 'selected' : '' }}>Vial</option>
                    </select>
                    @error('satuan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Stok Terkini <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="stok_terkini" value="{{ old('stok_terkini', $obat->stok_terkini) }}" required min="0"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">{{ $obat->satuan }}</span>
                    </div>
                    @error('stok_terkini')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Row 3: Deskripsi --}}
            <div>
                <label class="text-sm font-medium text-gray-600">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none"
                    placeholder="Deskripsi obat (opsional)">{{ old('deskripsi', $obat->deskripsi) }}</textarea>
                @error('deskripsi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="pt-2 flex space-x-3">
                <button type="submit" 
                    class="bg-[#2F9CCA] text-white px-6 py-2 rounded-lg hover:bg-[#1788b4] transition-all duration-200">
                    <i class="fa-solid fa-save mr-2"></i>
                    Update
                </button>
                <a href="{{ route('admin.stok-obat.index') }}"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-all duration-200">
                    <i class="fa-solid fa-times mr-2"></i>
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>
@endsection