@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Pasien')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl p-6 sm:p-8">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Patient Details</h2>
            <a href="{{ route('owner.pasien') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </a>
        </div>

        {{-- NAME CARD --}}
        <div class="bg-[#F9F9F9] border border-gray-200 rounded-lg p-4 mb-6">
            <h3 class="text-xl sm:text-[1.3rem] font-semibold text-gray-800">Susi Susanti</h3>
        </div>

        {{-- SECTION: IDENTITY --}}
        <div class="border border-gray-200 rounded-lg mb-6 overflow-hidden">

            {{-- SECTION TITLE --}}
            <div class="bg-gray-100 px-4 py-2 border-b border-gray-200">
                <h4 class="text-sm font-medium text-gray-600">Identity</h4>
            </div>
{{-- CONTENT GRID --}}
<div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-6">

    {{-- KOLOM KIRI --}}
    <div class="grid grid-cols-[150px_1fr] text-sm gap-y-3">
        <div class="text-gray-500">Patient ID:</div>
        <div class="font-medium text-gray-800">P-001</div>

        <div class="text-gray-500">First Name:</div>
        <div class="font-medium text-gray-800">Susi</div>

        <div class="text-gray-500">Last Name:</div>
        <div class="font-medium text-gray-800">Susanti</div>

        <div class="text-gray-500">Email:</div>
        <div class="font-medium text-gray-800">susi@gmail.com</div>
    </div>

    {{-- KOLOM KANAN --}}
    <div class="grid grid-cols-[150px_1fr] text-sm gap-y-3">
        <div class="text-gray-500">Phone:</div>
        <div class="font-medium text-gray-800">62822345678</div>

        <div class="text-gray-500">Date of Birth:</div>
        <div class="font-medium text-gray-800">24 Maret 2000</div>

        <div class="text-gray-500">Gender:</div>
        <div class="font-medium text-gray-800">Female</div>
    </div>

</div>


        </div>

        {{-- SECTION: REKAM MEDIS --}}
        <div class="border border-gray-200 rounded-lg overflow-hidden">

            {{-- SECTION TITLE --}}
            <div class="bg-gray-100 px-4 py-2 border-b border-gray-200">
                <h4 class="text-sm font-medium text-gray-600">Rekam Medis</h4>
            </div>

           {{-- CONTENT GRID --}}
<div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-6">

    {{-- KOLOM KIRI --}}
    <div class="grid grid-cols-[150px_1fr] text-sm gap-y-3">
        <div class="text-gray-500">Jenis Kulit:</div>
        <div class="font-medium text-gray-800">Dry</div>

        <div class="text-gray-500">Kelembapan:</div>
        <div class="font-medium text-gray-800">Cukup</div>

        <div class="text-gray-500">Acne:</div>
        <div class="font-medium text-gray-800">Tidak Ada</div>

        <div class="text-gray-500">Kerutan:</div>
        <div class="font-medium text-gray-800">Ada (Sedang)</div>

        <div class="text-gray-500">Hyperpigmentasi:</div>
        <div class="font-medium text-gray-800">Ada (Ringan)</div>

        <div class="text-gray-500">Scar:</div>
        <div class="font-medium text-gray-800">Tidak Ada</div>
    </div>

    {{-- KOLOM KANAN --}}
    <div class="grid grid-cols-[150px_1fr] text-sm gap-y-3">
        <div class="text-gray-500">Riwayat Penyakit:</div>
        <div class="font-medium text-gray-800">Tidak Ada</div>

        <div class="text-gray-500">Riwayat Alergi:</div>
        <div class="font-medium text-gray-800">Tidak Ada</div>

        <div class="text-gray-500">Riwayat Pengobatan:</div>
        <div class="font-medium text-gray-800">Tidak Ada</div>

        <div class="text-gray-500">Produk terakhir dipakai:</div>
        <div class="font-medium text-gray-800">Noroid</div>

        <div class="text-gray-500">Kondisi Pasien:</div>
        <div class="font-medium text-gray-800">Tidak Hamil/Menyusui</div>
    </div>

</div>


        </div>

    </div>
</div>

@endsection
