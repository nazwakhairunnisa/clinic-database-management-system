@extends('layouts.admin')

@section('pageTitle', 'Tambah Promo')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6 sm:p-8 transition-all duration-300">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-black">Add Promo</h2>
            <a href="{{ route('admin.promo.index') }}" 
               class="text-gray-400 hover:text-gray-600 transition">
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
        <form action="{{ route('admin.promo.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Treatment Dropdown --}}
            <div>
                <label class="text-sm font-medium text-gray-600">
                    Treatment <span class="text-red-500">*</span>
                </label>
                <select name="id_treatment" 
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-[#EED892] outline-none
                        @error('id_treatment') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                        required>
                    <option value="">Pilih Treatment</option>
                    @foreach($treatments as $treatment)
                        <option value="{{ $treatment->id_treatment }}" 
                                {{ old('id_treatment') == $treatment->id_treatment ? 'selected' : '' }}>
                            {{ $treatment->nama_treatment }} - {{ $treatment->formatted_harga }}
                        </option>
                    @endforeach
                </select>
                @error('id_treatment')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ROW 1: Nama Promo & Harga Promo --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Nama Promo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_promo" value="{{ old('nama_promo') }}"
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-[#EED892] outline-none
                        @error('nama_promo') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                        placeholder="Contoh: Promo Ramadan"
                        required>
                    @error('nama_promo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Harga Promo <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="harga_promo" value="{{ old('harga_promo') }}" 
                           step="0.01" min="0.01"
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-[#EED892] outline-none
                        @error('harga_promo') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                        placeholder="150000"
                        required>
                    @error('harga_promo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ROW 2: Periode --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Periode Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="periode_mulai" value="{{ old('periode_mulai') }}"
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-[#EED892] outline-none
                        @error('periode_mulai') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                        required>
                    @error('periode_mulai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Periode Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="periode_selesai" value="{{ old('periode_selesai') }}"
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-[#EED892] outline-none
                        @error('periode_selesai') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                        required>
                    @error('periode_selesai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- IMAGE UPLOAD --}}
            <div>
                <label class="text-sm font-medium text-gray-600">
                    Upload Image <span class="text-red-500">*</span>
                </label>
                <input type="file" name="gambar_promo" accept="image/*"
                    id="gambar_promo"
                    onchange="previewImage(event)"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-50
                    @error('gambar_promo') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                    required>
                @error('gambar_promo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">PNG, JPG, WebP — Max 2MB</p>

                {{-- PREVIEW --}}
                <div id="imagePreview" class="mt-3 hidden">
                    <p class="text-sm font-medium mb-2">Preview foto:</p>
                    <div class="relative inline-block">
                        <img id="preview" class="w-40 h-40 object-cover rounded-lg border-2 border-gray-300">
                        <button type="button" onclick="removeImage()" 
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- BUTTONS --}}
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('admin.promo.index') }}"
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </a>
                <button type="submit"
                    class="bg-[#2F9CCA] text-white px-6 py-2 rounded-lg hover:bg-[#1788b4] transition">
                    <i class="fa-solid fa-check mr-2"></i>
                    Simpan
                </button>
            </div>

        </form>

    </div>
</div>

{{-- SCRIPT PREVIEW --}}
<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const wrapper = document.getElementById('imagePreview');
    const file = event.target.files[0];

    if (file) {
        // Validasi size
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Max 2MB');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            wrapper.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    const input = document.getElementById('gambar_promo');
    const container = document.getElementById('imagePreview');
    input.value = '';
    container.classList.add('hidden');
}
</script>

@endsection