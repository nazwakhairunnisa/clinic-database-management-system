@extends('layouts.owner.app')

@section('pageTitle', 'Edit Promo')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl p-8 transition-all duration-300 relative">

        {{-- CLOSE BUTTON --}}
        <a href="{{ route('owner.promo') }}" class="absolute top-6 right-6 text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </a>

        {{-- TITLE --}}
        <h2 class="text-2xl font-semibold text-gray-800 mb-8">Edit Promo</h2>

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
        <form action="{{ route('owner.promo.update', $promo->promo_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Treatment Dropdown --}}
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    Treatment <span class="text-red-500">*</span>
                </label>
                <select name="id_treatment" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2
                        focus:ring-2 focus:ring-[#EED892] focus:outline-none
                        @error('id_treatment') border-red-500 @enderror"
                        required>
                    <option value="">Pilih Treatment</option>
                    @foreach($treatments as $treatment)
                        <option value="{{ $treatment->id_treatment }}" 
                                {{ old('id_treatment', $promo->id_treatment) == $treatment->id_treatment ? 'selected' : '' }}>
                            {{ $treatment->nama_treatment }} - {{ $treatment->formatted_harga }}
                        </option>
                    @endforeach
                </select>
                @error('id_treatment')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ROW 1 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                {{-- Nama Promo --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        Nama Promo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_promo" value="{{ old('nama_promo', $promo->nama_promo) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2
                        focus:ring-2 focus:ring-[#EED892] focus:outline-none
                        @error('nama_promo') border-red-500 @enderror"
                        required>
                    @error('nama_promo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Harga Promo --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        Harga Promo <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="harga_promo" value="{{ old('harga_promo', $promo->harga_promo) }}"
                           step="0.01" min="0.01"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2
                        focus:ring-2 focus:ring-[#EED892] focus:outline-none
                        @error('harga_promo') border-red-500 @enderror"
                        required>
                    @error('harga_promo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- ROW 2 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                {{-- Periode Mulai --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        Periode Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="periode_mulai" 
                           value="{{ old('periode_mulai', $promo->periode_mulai->format('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2
                        focus:ring-2 focus:ring-[#EED892] focus:outline-none
                        @error('periode_mulai') border-red-500 @enderror"
                        required>
                    @error('periode_mulai')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Periode Selesai --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        Periode Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="periode_selesai" 
                           value="{{ old('periode_selesai', $promo->periode_selesai->format('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2
                        focus:ring-2 focus:ring-[#EED892] focus:outline-none
                        @error('periode_selesai') border-red-500 @enderror"
                        required>
                    @error('periode_selesai')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Upload Image --}}
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    Upload Image
                </label>

                @if($promo->gambar_promo)
                <div class="mb-3" id="currentImage">
                    <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                    <img src="{{ $promo->gambar_url }}" alt="Current" class="w-48 h-48 object-cover rounded-lg border">
                </div>
                @endif

                <input type="file" name="gambar_promo" accept="image/*"
                    id="gambar_promo"
                    onchange="previewImage(event)"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50
                    @error('gambar_promo') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah gambar. PNG, JPG, WebP — Max 2MB</p>
                @error('gambar_promo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                {{-- Preview gambar baru --}}
                <div id="imagePreview" class="mt-3 hidden">
                    <p class="text-sm font-medium mb-2 text-green-600">Preview gambar baru:</p>
                    <div class="relative inline-block">
                        <img id="preview" class="w-48 h-48 object-cover rounded-lg border-2 border-green-300">
                        <button type="button" onclick="removeImage()" 
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 hover:bg-red-600">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- BUTTON SAVE --}}
            <div class="pt-2 flex justify-end space-x-3">
                <a href="{{ route('owner.promo') }}"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-all duration-200 shadow-sm">
                    Batal
                </a>
                <button type="submit"
                    class="bg-[#806B3F] text-white px-6 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fa-solid fa-check mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        // Validasi size
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Max 2MB');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
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