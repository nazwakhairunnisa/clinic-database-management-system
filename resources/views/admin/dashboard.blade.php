@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-12 gap-6">
    {{-- Card Statistik --}}
    <div class="col-span-6 md:col-span-3 bg-[#EED892] rounded-xl p-4 shadow">
        <p class="text-sm text-gray-700">Reservasi Hari Ini</p>
        <p class="text-3xl font-bold mt-2">5</p>
    </div>
    <div class="col-span-6 md:col-span-3 bg-[#F5EAD5] rounded-xl p-4 shadow">
        <p class="text-sm text-gray-700">Total Pasien</p>
        <p class="text-3xl font-bold mt-2">315</p>
    </div>

    {{-- Tabel Status Reservasi --}}
    <div class="col-span-12 md:col-span-7 bg-white rounded-xl p-5 shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Status Reservasi</h2>
            <button class="text-sm text-[#806B3F] hover:underline">View All</button>
        </div>
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="py-2 px-3">No</th>
                    <th class="py-2 px-3">Pasien</th>
                    <th class="py-2 px-3">Treatment</th>
                    <th class="py-2 px-3">Tanggal & Waktu</th>
                    <th class="py-2 px-3">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="py-2 px-3">1</td>
                    <td class="py-2 px-3">Susi Susanti</td>
                    <td class="py-2 px-3">Facial Rejuvenation</td>
                    <td class="py-2 px-3">2025-11-13 09:00</td>
                    <td class="py-2 px-3"><span class="bg-green-100 text-green-600 px-2 py-1 rounded text-xs">Completed</span></td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 px-3">2</td>
                    <td class="py-2 px-3">Aisyah Runa</td>
                    <td class="py-2 px-3">Laser Hair Removal</td>
                    <td class="py-2 px-3">2025-11-13 11:00</td>
                    <td class="py-2 px-3"><span class="bg-blue-100 text-blue-600 px-2 py-1 rounded text-xs">In Progress</span></td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 px-3">3</td>
                    <td class="py-2 px-3">Asap</td>
                    <td class="py-2 px-3">Botox Injection</td>
                    <td class="py-2 px-3">2025-11-13 13:00</td>
                    <td class="py-2 px-3"><span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded text-xs">Scheduled</span></td>
                </tr>
                <tr>
                    <td class="py-2 px-3">4</td>
                    <td class="py-2 px-3">Asap</td>
                    <td class="py-2 px-3">Botox Injection</td>
                    <td class="py-2 px-3">2025-11-13 17:00</td>
                    <td class="py-2 px-3"><span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs">Canceled</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Kalender & Jadwal --}}
    <div class="col-span-12 md:col-span-5 bg-white rounded-xl p-5 shadow">
        <h2 class="text-lg font-semibold mb-3">Jadwal Treatment</h2>
        <div class="border rounded-lg p-3 mb-4">
            <p class="text-gray-600 text-sm">Kamis, 13 November 2025</p>
            <ul class="mt-2 space-y-2">
                <li class="flex justify-between text-sm">
                    <span>Susi Susanti</span>
                    <span>09:00 - 10:30</span>
                </li>
                <li class="flex justify-between text-sm">
                    <span>Aisyah Runa</span>
                    <span>11:00 - 12:30</span>
                </li>
                <li class="flex justify-between text-sm">
                    <span>Asap</span>
                    <span>13:00 - 14:00</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
