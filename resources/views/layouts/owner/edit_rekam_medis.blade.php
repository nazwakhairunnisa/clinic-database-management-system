@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Pasien')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto'] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-8 sm:pt-12">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl p-6 sm:p-8 transition-all duration-300">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-black">Edit Rekam Medis Patient</h2>
            <a href="{{ route('owner.pasien') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </a>
        </div>

        {{-- Form --}}
        <form class="space-y-5 text-sm text-gray-700">
            {{-- Jenis Kulit --}}
            <div>
                <label class="font-medium text-gray-700">Jenis Kulit</label>
                <div class="flex flex-wrap gap-4 mt-2">
                    @foreach(['Normal','Dry','Oily','Sensitive','Kombinasi'] as $jenis)
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="jenis_kulit" class="text-[#0073d9] focus:ring-[#EED892]">
                            <span>{{ $jenis }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Kelembapan --}}
            <div>
                <label class="font-medium text-gray-700">Kelembapan</label>
                <div class="flex flex-wrap gap-4 mt-2">
                    @foreach(['Baik','Cukup','Kurang'] as $kelembapan)
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="kelembapan" class="text-[#0073d9] focus:ring-[#EED892]">
                            <span>{{ $kelembapan }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Acne, Hyperpigmentasi, Kerutan, Scar --}}
            @foreach(['Acne','Hyperpigmentasi','Kerutan','Scar'] as $bagian)
                <div>
                    <label class="font-medium text-gray-700">{{ $bagian }}</label>
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="{{ strtolower($bagian) }}" class="text-[#0073d9] focus:ring-[#EED892]">
                            <span>Ada</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="{{ strtolower($bagian) }}" class="text-[#0073d9] focus:ring-[#EED892]">
                            <span>Tidak Ada</span>
                        </label>

                        <input type="text" placeholder="Area" class="border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-[#EED892] outline-none">
                        @if($bagian == 'Scar')
                            <input type="text" placeholder="Jenis" class="border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-[#EED892] outline-none">
                        @endif

                        <span class="ml-2 text-xs text-gray-500">Derajat:</span>
                        @foreach(['Ringan','Sedang','Berat'] as $level)
                            <label class="flex items-center space-x-1">
                                <input type="radio" name="{{ strtolower($bagian) }}_level" class="text-[#0073D9]focus:ring-[#EED892]">
                                <span class="text-xs">{{ $level }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Kondisi Pasien --}}
            <div>
                <label class="font-medium text-gray-700">Kondisi Pasien</label>
                <div class="flex flex-wrap gap-4 mt-2">
                    @foreach(['Hamil','Menyusui','Kontrasepsi','Tidak Hamil/Menyusui'] as $kondisi)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="text-0073D9focus:ring-[#EED892]">
                            <span>{{ $kondisi }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Produk & Riwayat --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="font-medium text-gray-700">Produk/Peralatan terakhir dipakai</label>
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>
                <div>
                    <label class="font-medium text-gray-700">Riwayat Penyakit yang Diderita</label>
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="font-medium text-gray-700">Riwayat Pengobatan</label>
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>
                <div>
                    <label class="font-medium text-gray-700">Riwayat Alergi</label>
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                </div>
            </div>

            {{-- Save Button --}}
            <div class="pt-4 flex justify-start">
                <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white px-6 py-2 rounded-lg transition-all duration-300">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
