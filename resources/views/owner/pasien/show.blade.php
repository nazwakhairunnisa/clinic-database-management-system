@extends('layouts.owner.app')

@section('pageTitle', 'Detail Pasien')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl p-6 sm:p-8">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Patient Details</h2>
            <div class="flex gap-2">
                {{-- Edit Rekam Medis Button --}}
                <a href="{{ route('owner.pasien.editRekam', $pasien->id_pasien) }}" 
                    class="text-blue-600 hover:text-blue-800 transition"
                    title="Edit Rekam Medis">
                    <i class="fa-solid fa-edit text-xl"></i>
                </a>
                {{-- Close Button --}}
                <a href="{{ route('owner.pasien.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </a>
            </div>
        </div>

        {{-- NAME CARD --}}
        <div class="bg-[#F9F9F9] border border-gray-200 rounded-lg p-4 mb-6">
            <h3 class="text-xl sm:text-[1.3rem] font-semibold text-gray-800">
                {{ $pasien->nama_lengkap }}
            </h3>
            <div class="flex gap-4 mt-2 text-sm text-gray-600">
                <span>
                    <i class="fa-solid fa-birthday-cake mr-1"></i>
                    {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d M Y') }} ({{ $pasien->usia }} tahun)
                </span>
                <span>
                    <i class="fa-solid fa-venus-mars mr-1"></i>
                    {{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                </span>
            </div>
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
                    <div class="font-medium text-gray-800">P-{{ str_pad($pasien->id_pasien, 4, '0', STR_PAD_LEFT) }}</div>

                    <div class="text-gray-500">First Name:</div>
                    <div class="font-medium text-gray-800">{{ $pasien->nama_depan }}</div>

                    <div class="text-gray-500">Last Name:</div>
                    <div class="font-medium text-gray-800">{{ $pasien->nama_belakang }}</div>

                    <div class="text-gray-500">Alamat:</div>
                    <div class="font-medium text-gray-800">{{ $pasien->alamat }}</div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="grid grid-cols-[150px_1fr] text-sm gap-y-3">
                    <div class="text-gray-500">Phone:</div>
                    <div class="font-medium text-gray-800">{{ $pasien->no_telepon }}</div>

                    <div class="text-gray-500">Date of Birth:</div>
                    <div class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d F Y') }}</div>

                    <div class="text-gray-500">Gender:</div>
                    <div class="font-medium text-gray-800">{{ $pasien->jenis_kelamin == 'L' ? 'Male' : 'Female' }}</div>

                    <div class="text-gray-500">Registered by:</div>
                    <div class="font-medium text-gray-800">{{ $pasien->didaftarkan_oleh ?? '-' }}</div>

                    <div class="text-gray-500">Tanggal Daftar:</div>
                    <div class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($pasien->tanggal_daftar)->format('d F Y') }}</div>
                </div>

            </div>
        </div>

        {{-- STATISTICS --}}
        @if($pasien->total_kunjungan > 0)
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-blue-700">{{ $pasien->total_kunjungan }}</div>
                <div class="text-sm text-gray-600">Total Kunjungan</div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-green-700">{{ $pasien->kunjungan_selesai }}</div>
                <div class="text-sm text-gray-600">Kunjungan Selesai</div>
            </div>
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-purple-700">Rp {{ number_format($pasien->total_belanja ?? 0, 0, ',', '.') }}</div>
                <div class="text-sm text-gray-600">Total Belanja</div>
            </div>
        </div>
        @endif

        {{-- SECTION: REKAM MEDIS --}}
        @if($pasien->id_rekam_medis)
        <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">

            {{-- SECTION TITLE --}}
            <div class="bg-gray-100 px-4 py-2 border-b border-gray-200 flex justify-between items-center">
                <h4 class="text-sm font-medium text-gray-600">Rekam Medis</h4>
                <span class="text-xs text-gray-500">
                    Dibuat: {{ \Carbon\Carbon::parse($pasien->tanggal_rekam_medis_awal)->format('d M Y') }}
                </span>
            </div>

            {{-- CONTENT GRID --}}
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-6">

                {{-- KOLOM KIRI --}}
                <div class="grid grid-cols-[150px_1fr] text-sm gap-y-3">
                    <div class="text-gray-500">Keluhan Awal:</div>
                    <div class="font-medium text-gray-800">{{ $pasien->keluhan_awal ?? '-' }}</div>

                    <div class="text-gray-500">Jenis Kulit:</div>
                    <div class="font-medium text-gray-800">{{ ucfirst($pasien->jenis_kulit ?? '-') }}</div>

                    <div class="text-gray-500">Kelembapan:</div>
                    <div class="font-medium text-gray-800">{{ ucfirst($pasien->kelembapan ?? '-') }}</div>

                    <div class="text-gray-500">Kondisi Pasien:</div>
                    <div class="font-medium text-gray-800">{{ ucfirst($pasien->kondisi_pasien ?? '-') }}</div>

                    <div class="text-gray-500">Produk Terakhir:</div>
                    <div class="font-medium text-gray-800">{{ $pasien->produk_terakhir_dipakai ?? '-' }}</div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="grid grid-cols-[150px_1fr] text-sm gap-y-3">
                    <div class="text-gray-500">Riwayat Penyakit:</div>
                    <div class="font-medium text-gray-800">{{ $pasien->riwayat_penyakit ?? '-' }}</div>

                    <div class="text-gray-500">Riwayat Alergi:</div>
                    <div class="font-medium text-gray-800">{{ $pasien->riwayat_alergi ?? '-' }}</div>

                    <div class="text-gray-500">Riwayat Pengobatan:</div>
                    <div class="font-medium text-gray-800">{{ $pasien->riwwayat_pengobatan ?? '-' }}</div>
                </div>

            </div>

            {{-- KONDISI KULIT DETAIL --}}
            @if($kondisiKulit->count() > 0)
            <div class="border-t border-gray-200 p-5">
                <h5 class="text-sm font-semibold text-gray-700 mb-3">Detail Kondisi Kulit</h5>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($kondisiKulit as $jenis => $items)
                        @foreach($items as $kondisi)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="font-medium text-gray-800 mb-2">{{ ucfirst($kondisi->jenis_kondisi) }}</div>
                            <div class="text-xs space-y-1">
                                <div>
                                    <span class="text-gray-500">Status:</span>
                                    <span class="font-medium {{ $kondisi->status_kondisi == 'ada' ? 'text-red-600' : 'text-green-600' }}">
                                        {{ ucfirst($kondisi->status_kondisi) }}
                                    </span>
                                </div>
                                @if($kondisi->status_kondisi == 'ada')
                                <div>
                                    <span class="text-gray-500">Area:</span>
                                    <span class="font-medium">{{ $kondisi->area }}</span>
                                </div>
                                @if($kondisi->derajat)
                                <div>
                                    <span class="text-gray-500">Derajat:</span>
                                    <span class="font-medium">{{ ucfirst($kondisi->derajat) }}</span>
                                </div>
                                @endif
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
            @endif

        </div>
        @else
        <div class="border border-yellow-300 bg-yellow-50 rounded-lg p-4 mb-6 text-center">
            <p class="text-yellow-700 mb-2">Rekam medis belum dibuat</p>
            <a href="{{ route('owner.pasien.editRekam', $pasien->id_pasien) }}" 
                class="text-blue-600 hover:text-blue-800 font-medium">
                Buat Rekam Medis Sekarang
            </a>
        </div>
        @endif

        {{-- SECTION: KUNJUNGAN TERAKHIR --}}
        @if($pasien->kunjungan_terakhir_date)
        <div class="border border-gray-200 rounded-lg overflow-hidden">

            {{-- SECTION TITLE --}}
            <div class="bg-gray-100 px-4 py-2 border-b border-gray-200">
                <h4 class="text-sm font-medium text-gray-600">Kunjungan Terakhir</h4>
            </div>

            {{-- CONTENT --}}
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-6">
                <div class="grid grid-cols-[150px_1fr] text-sm gap-y-3">
                    <div class="text-gray-500">Tanggal:</div>
                    <div class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($pasien->kunjungan_terakhir_date)->format('d F Y') }}</div>

                    <div class="text-gray-500">Kondisi Terkini:</div>
                    <div class="font-medium text-gray-800">{{ $pasien->kondisi_terkini ?? '-' }}</div>
                </div>

                <div class="grid grid-cols-[150px_1fr] text-sm gap-y-3">
                    <div class="text-gray-500">Riwayat Eksfo:</div>
                    <div class="font-medium text-gray-800">{{ $pasien->riwayat_eksfo ?? '-' }}</div>

                    <div class="text-gray-500">Terapi Terakhir:</div>
                    <div class="font-medium text-gray-800">{{ $pasien->terapi_terakhir ?? '-' }}</div>
                </div>
            </div>

            {{-- FOTO BEFORE/AFTER --}}
            @if($pasien->foto_before_last || $pasien->foto_after_last)
            <div class="border-t border-gray-200 p-5">
                <h5 class="text-sm font-semibold text-gray-700 mb-3">Foto Treatment</h5>
                <div class="grid grid-cols-2 gap-4">
                    @if($pasien->foto_before_last)
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Sebelum Treatment</p>
                        <img src="{{ asset('storage/' . $pasien->foto_before_last) }}" 
                            alt="Before" 
                            class="w-full rounded-lg border border-gray-300">
                    </div>
                    @endif
                    @if($pasien->foto_after_last)
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Sesudah Treatment</p>
                        <img src="{{ asset('storage/' . $pasien->foto_after_last) }}" 
                            alt="After" 
                            class="w-full rounded-lg border border-gray-300">
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>
        @endif

    </div>
</div>

@endsection