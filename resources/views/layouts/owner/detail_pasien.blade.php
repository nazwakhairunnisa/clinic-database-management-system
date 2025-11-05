@extends('layouts.owner.app')

@section('pageTitle', 'Patient Details')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-gray-100 min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl p-6 sm:p-8 transition-all duration-300">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-[#806B3F]">Patient Details</h2>
            <a href="{{ route('owner.pasien') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </a>
        </div>

        {{-- Nama Pasien --}}
        <div class="bg-[#F9F9F9] rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Susi Susanti</h3>
        </div>

        {{-- Identity Section --}}
        <div class="border border-gray-200 rounded-lg mb-6">
            <div class="bg-gray-100 border-b border-gray-200 px-4 py-2 rounded-t-lg">
                <h4 class="text-sm font-medium text-gray-700">Identity</h4>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 p-4 text-sm text-gray-700">
                <div><span class="font-medium text-gray-500">Patient ID:</span> P-001</div>
                <div><span class="font-medium text-gray-500">Phone:</span> 62822345678</div>
                <div><span class="font-medium text-gray-500">First Name:</span> Susi</div>
                <div><span class="font-medium text-gray-500">Date of Birth:</span> 24 Maret 2000</div>
                <div><span class="font-medium text-gray-500">Last Name:</span> Susanti</div>
                <div><span class="font-medium text-gray-500">Gender:</span> Female</div>
                <div class="sm:col-span-2"><span class="font-medium text-gray-500">Email:</span> susi@gmail.com</div>
            </div>
        </div>

        {{-- Rekam Medis Section --}}
        <div class="border border-gray-200 rounded-lg">
            <div class="bg-gray-100 border-b border-gray-200 px-4 py-2 rounded-t-lg">
                <h4 class="text-sm font-medium text-gray-700">Rekam Medis</h4>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 p-4 text-sm text-gray-700">
                <div><span class="font-medium text-gray-500">Jenis Kulit:</span> Dry</div>
                <div><span class="font-medium text-gray-500">Riwayat Penyakit:</span> Tidak Ada</div>

                <div><span class="font-medium text-gray-500">Kelembapan:</span> Cukup</div>
                <div><span class="font-medium text-gray-500">Riwayat Alergi:</span> Tidak Ada</div>

                <div><span class="font-medium text-gray-500">Acne:</span> Tidak Ada</div>
                <div><span class="font-medium text-gray-500">Riwayat Pengobatan:</span> Tidak Ada</div>

                <div><span class="font-medium text-gray-500">Kerutan:</span> Ada (Sedang)</div>
                <div><span class="font-medium text-gray-500">Produk yang terakhir dipakai:</span> Noroid</div>

                <div><span class="font-medium text-gray-500">Hyperpigmentasi:</span> Ada (Ringan)</div>
                <div><span class="font-medium text-gray-500">Scar:</span> Tidak Ada</div>

                <div class="sm:col-span-2"><span class="font-medium text-gray-500">Kondisi Pasien:</span> Tidak Hamil/Menyusui</div>
            </div>
        </div>
    </div>
</div>
@endsection
