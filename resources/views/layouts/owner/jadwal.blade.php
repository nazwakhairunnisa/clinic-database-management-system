@extends('layouts.owner.app')

@section('pageTitle', 'Jadwal Reservasi')

@section('content')

{{-- Import font Roboto --}}
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

{{-- Style tambahan untuk hilangkan arrow & styling tabel --}}
<style>
/* Hilangkan ikon dropdown default */
select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: none !important;
}

/* Tabel dengan garis hitam antar baris */
.table-bordered tbody tr {
    border-bottom: 1px solid #000;
}
</style>

<div class="max-w-full font-['Roboto',sans-serif]">

    {{-- HEADER FILTER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">

        <div class="flex flex-wrap gap-2 sm:gap-3 items-center w-full sm:w-auto">

            {{-- Search --}}
            <div class="relative flex-grow sm:flex-grow-0">
                <input type="text" placeholder="Search patient, treatments, etc"
                    class="border border-white rounded-full pl-10 pr-4 py-2 w-full sm:w-60 focus:ring-2 focus:ring-[#EED892] focus:outline-none text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
            </div>

            {{-- Dropdown Treatments --}}
            <select class="bg-[#EED892] text-[#4A3B1C] px-4 py-2 rounded-full text-sm focus:outline-none appearance-none">
                <option>Treatments</option>
                <option>Facial</option>
                <option>Laser</option>
            </select>

            <style>
                
            /* Style untuk opsi dropdown (option) agar putih dengan teks hitam */
            select option {
            background-color: white;
            color: black;
            }
            </style>


            {{-- Input Rentang Tanggal --}}
            <input type="date" class="bg-[#EED892] text-[#4A3B1C] px-3 py-2 rounded-full text-sm focus:outline-none">

            {{-- Dropdown Status --}}
            <select class="bg-[#EED892] text-[#4A3B1C] px-4 py-2 rounded-full text-sm focus:outline-none appearance-none">
                <option>Status</option>
                <option>Completed</option>
                <option>In Progress</option>
                <option>Scheduled</option>
                <option>Cancelled</option>
            </select>

        </div>

        {{-- Export Button (digeser kiri) --}}
        <div class="w-full sm:w-auto mt-4 sm:mt-0">
            <button class="bg-[#806B3F] text-white px-4 py-2 rounded-lg hover:bg-[#A18F5E] transition text-sm flex items-center justify-center">
    <i class="fa-solid fa-file-export text-base sm:text-sm"></i>
    <span class="hidden sm:inline ml-2">Export</span>
</button>

        </div>
    </div>

     {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto">
        <table class="min-w-[700px] w-full text-sm text-left table-bordered border-collapse">
            <thead>
                <tr class="bg-gray-100/60 text-gray-700 font-normal">
                    <th class="px-4 py-3 rounded-l-lg whitespace-nowrap border-b border-black">No</th>
                    <th class="px-4 py-3 whitespace-nowrap border-b border-black">ID Patient</th>
                    <th class="px-4 py-3 whitespace-nowrap border-b border-black">Patient</th>
                    <th class="px-4 py-3 whitespace-nowrap border-b border-black">Treatment</th>
                    <th class="px-4 py-3 whitespace-nowrap border-b border-black">Date & Time</th>
                    <th class="px-4 py-3 rounded-r-lg whitespace-nowrap border-b border-black">Status</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach([
                    ['no'=>1,'id'=>'P-001','name'=>'Susi Susanti','treatment'=>'Facial Rejuvenation','datetime'=>'1–5 Oktober 2025','status'=>'Completed','statusColor'=>'green'],
                    ['no'=>2,'id'=>'P-002','name'=>'Aliyah Runa','treatment'=>'Laser Hair Removal','datetime'=>'2–6 Oktober 2025','status'=>'In Progress','statusColor'=>'blue'],
                    ['no'=>3,'id'=>'P-003','name'=>'Asep','treatment'=>'Botox Injection','datetime'=>'3–7 Oktober 2025','status'=>'Scheduled','statusColor'=>'yellow'],
                    ['no'=>4,'id'=>'P-004','name'=>'Rudi Hartono','treatment'=>'Chemical Peels','datetime'=>'4–8 Oktober 2025','status'=>'Cancelled','statusColor'=>'red'],
                ] as $row)
                     <tr class="bg-white hover:bg-gray-50 transition-all duration-200 border-b border-gray-200">
                        <td class="px-4 py-3 rounded-l-lg whitespace-nowrap">{{ $row['no'] }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $row['id'] }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $row['name'] }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $row['treatment'] }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $row['datetime'] }}</td>
                        <td class="px-4 py-3 rounded-r-lg whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $row['statusColor']=='green' ? 'bg-green-200 text-green-700' : '' }}
                                {{ $row['statusColor']=='blue' ? 'bg-blue-200 text-blue-700' : '' }}
                                {{ $row['statusColor']=='yellow' ? 'bg-yellow-200 text-yellow-700' : '' }}
                                {{ $row['statusColor']=='red' ? 'bg-red-200 text-red-700' : '' }}">
                                {{ $row['status'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
