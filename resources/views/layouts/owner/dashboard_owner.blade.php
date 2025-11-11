@extends('layouts.owner.app')

@section('pageTitle', 'Dashboard')

@section('content')
<div class="p-4 sm:p-6 space-y-8 ">

    {{-- === Bagian Summary + Calendar & Jadwal Treatment (Sejajar) === --}}
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- === KIRI: Summary + Patient Status === --}}
        <div class="flex-1 flex flex-col space-y-6 min-w-0">

            {{-- === Summary Cards (2x2) === --}}
            <div class="grid grid-cols-2 gap-4 w-full">
                {{-- Pendapatan --}}
                <div class="bg-[#EED892] p-4 rounded-xl shadow flex items-center w-full h-28">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#FBF7E7]/80 mr-3">
                        <span class="iconify text-2xl text-[#806B3F]" data-icon="mdi:cash"></span>
                    </div>
                    <div class="flex flex-col justify-center text-left">
                        <p class="text-sm text-gray-600">Pendapatan</p>
                        <h2 class="text-xl font-bold text-[#806B3F]">
                            <span class="hidden sm:inline">Rp 5.400.000</span>
                            <span class="inline sm:hidden">Rp 5.4 jt</span>
                        </h2>
                    </div>
                </div>

                {{-- Total Pasien --}}
                <div class="bg-[#F5EAD5] p-4 rounded-xl shadow flex items-center w-full h-28">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#FBF7E7]/80 mr-3">
                        <span class="iconify text-2xl text-[#806B3F]" data-icon="mdi:account-group"></span>
                    </div>
                    <div class="flex flex-col justify-center text-left">
                        <p class="text-sm text-gray-600">Total Pasien</p>
                        <h2 class="text-xl font-bold text-[#806B3F]">315</h2>
                    </div>
                </div>

                {{-- Reservasi --}}
                <div class="bg-[#F5EAD5] p-4 rounded-xl shadow flex items-center w-full h-28">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#FBF7E7]/80 mr-3">
                        <span class="iconify text-2xl text-[#806B3F]" data-icon="mdi:calendar-check"></span>
                    </div>
                    <div class="flex flex-col justify-center text-left">
                        <p class="text-sm text-gray-600">Reservasi</p>
                        <h2 class="text-xl font-bold text-[#806B3F]">150</h2>
                    </div>
                </div>

                {{-- Total Treatment --}}
                <div class="bg-[#EED892] p-4 rounded-xl shadow flex items-center w-full h-28">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#FBF7E7]/80 mr-3">
                        <span class="iconify text-2xl text-[#806B3F]" data-icon="mdi:stethoscope"></span>
                    </div>
                    <div class="flex flex-col justify-center text-left">
                        <p class="text-sm text-gray-600">Total Treatment</p>
                        <h2 class="text-xl font-bold text-[#806B3F]">95</h2>
                    </div>
                </div>
            </div>

            {{-- === Patient Status Table === --}}
            <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

            <div class="bg-white p-6 rounded-xl shadow overflow-x-auto font-roboto" x-data="{
                patients: [
                    { id: 1, name: 'Susi Susanti', treatment: 'Facial Rejuvenation', date: '2025-10-13 09:00', status: 'Completed' },
                    { id: 2, name: 'Aliyah Runa', treatment: 'Laser Hair Removal', date: '2025-10-13 11:00', status: 'In Progress' },
                    { id: 3, name: 'Asep', treatment: 'Botox Injection', date: '2025-10-13 13:20', status: 'Scheduled' },
                    { id: 4, name: 'Rudi Hartono', treatment: 'Chemical Peels', date: '2025-10-14 10:00', status: 'Cancelled' },
                ],
                sortBy: '',
                sortAsc: true,
                sort(field) {
                    if (this.sortBy === field) {
                        this.sortAsc = !this.sortAsc;
                    } else {
                        this.sortBy = field;
                        this.sortAsc = true;
                    }
                    this.patients.sort((a, b) => {
                        let valA = a[field].toString().toLowerCase();
                        let valB = b[field].toString().toLowerCase();
                        return this.sortAsc ? valA.localeCompare(valB) : valB.localeCompare(valA);
                    });
                }
            }">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-black">Patient Status</h2>
                    <button class="bg-[#EBDDAF] text-[#806B3F] text-sm font-semibold px-4 py-1 rounded-lg hover:bg-[#e2d193] transition">
                        View All
                    </button>
                </div>

                <table class="w-full border-collapse text-sm min-w-[600px]">
                    <thead>
                        <tr class="bg-gray-100/50 rounded-lg">
                            <th class="py-2 px-3 text-black text-left rounded-l-lg">No</th>
                            <th class="py-2 px-3 text-black text-left">Patient</th>
                            <th class="py-2 px-3 text-black text-left">Treatment</th>
                            <th class="py-2 px-3 text-black text-left cursor-pointer select-none" @click="sort('date')">Date & Time</th>
                            <th class="py-2 px-3 text-black text-left rounded-r-lg cursor-pointer select-none">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <template x-for="(patient, index) in patients" :key="patient.id">
                            <tr class="bg-gray-50/30 rounded-lg">
                                <td class="py-2 px-3" x-text="index + 1"></td>
                                <td class="py-2 px-3" x-text="patient.name"></td>
                                <td class="py-2 px-3" x-text="patient.treatment"></td>
                                <td class="py-2 px-3" x-text="patient.date"></td>
                                <td class="py-2 px-3">
                                    <select x-model="patient.status"
                                            class="border-none text-xs rounded-lg px-2 py-1 font-semibold w-full"
                                            :class="{
                                                'bg-green-100 text-green-700': patient.status === 'Completed',
                                                'bg-blue-100 text-blue-700': patient.status === 'In Progress',
                                                'bg-gray-100 text-gray-700': patient.status === 'Scheduled',
                                                'bg-red-100 text-red-700': patient.status === 'Cancelled'
                                            }">
                                        <option class="bg-white text-black">Completed</option>
                                        <option class="bg-white text-black">In Progress</option>
                                        <option class="bg-white text-black">Scheduled</option>
                                        <option class="bg-white text-black">Cancelled</option>
                                    </select>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

        </div>

       {{-- === KANAN: Calendar + Jadwal Treatment === --}}
<div class="w-full lg:w-[34%] flex flex-col gap-6" x-data="calendarComponent()" x-init="initCalendar()">
    {{-- Calendar --}}
    <div class="bg-white p-4 md:p-6 rounded-xl shadow">
        {{-- Header Dropdown Bulan & Tahun --}}
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <!-- Dropdown Bulan -->
                <div class="relative">
                    <select x-model.number="currentMonth" @change="updateCalendar()" 
                        class="appearance-none bg-transparent border-none text-base font-bold pr-5 cursor-pointer outline-none focus:ring-0">
                        <template x-for="(month, index) in monthNames" :key="index">
                            <option :value="index" x-text="month"></option>
                        </template>
                    </select>
                    <span class="absolute right-1 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <i class="fa-solid text-xs"></i>
                    </span>
                </div>

                <!-- Dropdown Tahun -->
                <div class="relative">
                    <select x-model.number="currentYear" @change="updateCalendar()" 
                        class="appearance-none bg-transparent border-none text-base font-bold pr-10 cursor-pointer outline-none focus:ring-0">
                        <template x-for="year in yearsRange" :key="year">
                            <option :value="year" x-text="year"></option>
                        </template>
                    </select>
                    <span class="absolute right-0 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <i class="fa-solid text-xs"></i>
                    </span>
                </div>
            </div>
        </div>

        {{-- Hari --}}
        <div class="mt-3 text-xs text-gray-400">
            {{-- Header Nama Hari --}}
            <div class="grid grid-cols-7 text-center mb-1">
                <template x-for="day in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']">
                    <span x-text="day" class="font-semibold"></span>
                </template>
            </div>

            {{-- Tanggal --}}
            <div class="grid grid-cols-7 text-center gap-y-1">
                <template x-for="blank in firstDayOfMonth">
                    <div></div>
                </template>

                <template x-for="n in daysInMonth" :key="n">
                    <div 
                        @click="selectedDate = n"
                        class="flex items-center justify-center w-8 h-8 mx-auto rounded-full cursor-pointer transition"
                        :class="selectedDate === n 
                            ? 'bg-[#0073D9] text-white font-bold' 
                            : 'hover:bg-gray-100 text-gray-800'">
                        <span x-text="n"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Jadwal Treatment --}}
    <div class="bg-white p-4 md:p-6 rounded-xl shadow">
        <div class="flex justify-between items-center mb-3">
            <div>
                <h3 class="text-sm font-bold text-[#000000]">Jadwal Treatment</h3>
                <p class="text-xs text-gray-500" x-text="selectedDate + ' ' + monthNames[currentMonth] + ' ' + currentYear"></p>
            </div>
            <button class="text-[#806B3F] text-lg hover:text-[#A18F5E]">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>

           <ul class="divide-y divide-black text-xs md:text-sm">
            <li class="flex items-center justify-between py-2">
                <div class="flex items-center gap-2">
                    <input type="checkbox" checked class="accent-[#8CF69C]" />
                    <span class="text-gray-700">Susi Susanti</span>
                </div>
                <span class="text-gray-500">09.00 - 10.30</span>
            </li>
            <li class="flex items-center justify-between py-2">
                <div class="flex items-center gap-2">
                    <input type="checkbox" class="accent-[#8CF69C]" />
                    <span class="text-gray-700">Aliyah Runa</span>
                </div>
                <span class="text-gray-500">11.00 - 12.30</span>
            </li>
            <li class="flex items-center justify-between py-2">
                <div class="flex items-center gap-2">
                    <input type="checkbox" class="accent-[#8CF69C]" />
                    <span class="text-gray-700">Asep</span>
                </div>
                <span class="text-gray-500">13.20 - 15.00</span>
            </li>
        </ul>
    </div>
</div>

<script>
function calendarComponent() {
    return {
        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),
        selectedDate: new Date().getDate(),
        daysInMonth: [],
        firstDayOfMonth: 0,
        monthNames: [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ],
        yearsRange: [],
        initCalendar() {
            const yNow = new Date().getFullYear();
            this.yearsRange = Array.from({ length: 11 }, (_, i) => yNow - 5 + i);
            this.updateCalendar();
        },
        updateCalendar() {
            const days = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
            this.daysInMonth = Array.from({ length: days }, (_, i) => i + 1);
            this.firstDayOfMonth = new Date(this.currentYear, this.currentMonth, 1).getDay();
        }
    }
}
</script>


    </div>

    {{-- === MOST POPULAR TREATMENTS (Paling bawah, full width) === --}}
    <div class="bg-white p-2 rounded-xl shadow w-full max-w-[600px] overflow-x-auto">

        <h2 class="text-lg font-semibold text-black mb-3 mt-1">Most Popular Treatments</h2>

        <div class="space-y-3">
            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-3">
                    <span class="bg-[#EED892]/50 text-[#806B3F] text-sm font-semibold px-3 py-1 rounded-md flex items-center justify-center">#1</span>
                    <span class="text-gray-900 font-medium">Facial Rejuvenation</span>
                </div>
                <span class="text-sm text-[#808080]">423 reservations</span>
            </div>
            <hr class="border-gray-200">

            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-3">
                    <span class="bg-[#EED892]/50 text-[#806B3F] text-sm font-semibold px-3 py-1 rounded-md flex items-center justify-center">#2</span>
                    <span class="text-gray-900 font-medium">Laser Hair Removal</span>
                </div>
                <span class="text-sm text-[#808080]">354 reservations</span>
            </div>
            <hr class="border-gray-200">

            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-3">
                    <span class="bg-[#EED892]/50 text-[#806B3F] text-sm font-semibold px-3 py-1 rounded-md flex items-center justify-center">#3</span>
                    <span class="text-gray-900 font-medium">Botox Injection</span>
                </div>
                <span class="text-sm text-[#808080]">177 reservations</span>
            </div>
            <hr class="border-gray-200">

            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-3">
                    <span class="bg-[#EED892]/50 text-[#806B3F] text-sm font-semibold px-3 py-1 rounded-md flex items-center justify-center">#4</span>
                    <span class="text-gray-900 font-medium">Chemical Peels</span>
                </div>
                <span class="text-sm text-[#808080]">100 reservations</span>
            </div>
        </div>
    </div>

</div>

@endsection
