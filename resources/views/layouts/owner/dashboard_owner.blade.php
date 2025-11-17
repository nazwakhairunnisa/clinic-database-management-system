@extends('layouts.owner.app')

@section('pageTitle', 'Dashboard')

@section('content')
<div class="p-4 sm:p-6 space-y-8 font-['Roboto']">

    {{-- === Bagian Summary + Calendar & Jadwal Treatment (Sejajar) === --}}
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- === KIRI: Summary + Patient Status === --}}
        <div class="flex-1 flex flex-col space-y-6 min-w-0">

            {{-- === Summary Cards (2x2) === --}}
            <div class="grid grid-cols-2 gap-4 w-full">
                {{-- Pendapatan --}}
                <div class="bg-[#EED892] p-4 rounded-xl shadow flex items-center w-full h-28">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#FBF7E7]/80 mr-3">
                       <iconify-icon icon="dashicons:money-alt" class="text-3xl text-black"></iconify-icon>
                    </div>
                    <div class="flex flex-col justify-center text-left">
                        <p class="text-sm text-gray-600">Pendapatan</p>
                        <h2 class="text-xl font-bold text-black">
                            <span class="hidden sm:inline">Rp 5.400.000</span>
                            <span class="inline sm:hidden">Rp 5.4 jt</span>
                        </h2>
                    </div>
                </div>

                {{-- Total Pasien --}}
                <div class="bg-[#F5EAD5] p-4 rounded-xl shadow flex items-center w-full h-28">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#FBF7E7]/80 mr-3">
                        <iconify-icon icon="mdi:people-outline" class="text-2xl text-black"></iconify-icon>
                    </div>
                    <div class="flex flex-col justify-center text-left">
                        <p class="text-sm text-gray-600">Total Pasien</p>
                        <h2 class="text-xl font-bold text-black">315</h2>
                    </div>
                </div>

                {{-- Reservasi --}}
                <div class="bg-[#F5EAD5] p-4 rounded-xl shadow flex items-center w-full h-28">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#FBF7E7]/80 mr-3">
                        <iconify-icon icon="mdi:calendar-check" class="text-2xl text-black"></iconify-icon>
                    </div>
                    <div class="flex flex-col justify-center text-left">
                        <p class="text-sm text-gray-600">Reservasi</p>
                        <h2 class="text-xl font-bold text-black">150</h2>
                    </div>
                </div>

                {{-- Total Treatment --}}
                <div class="bg-[#EED892] p-4 rounded-xl shadow flex items-center w-full h-28">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#FBF7E7]/80 mr-3">
                       <iconify-icon icon="ri:health-book-line" class="text-2xl text-black"></iconify-icon>
                    </div>
                    <div class="flex flex-col justify-center text-left">
                        <p class="text-sm text-gray-600">Total Treatment</p>
                        <h2 class="text-xl font-bold text-black">95</h2>
                    </div>
                </div>
            </div>

            {{-- === PATIENT STATUS (FINAL CODE) === --}}
<div class="bg-white p-6 rounded-xl shadow overflow-x-auto font-['Roboto']"
    x-data="{
        patients: [
            { id: 1, name: 'Susi Susanti', treatment: 'Facial Rejuvenation', date: '2025-10-13 09:00', status: 'Completed' },
            { id: 2, name: 'Aliyah Runa', treatment: 'Laser Hair Removal', date: '2025-10-13 11:00', status: 'In Progress' },
            { id: 3, name: 'Asep', treatment: 'Botox Injection', date: '2025-10-13 13:20', status: 'Scheduled' },
            { id: 4, name: 'Rudi Hartono', treatment: 'Chemical Peels', date: '2025-10-14 10:00', status: 'Cancelled' },
        ],

        treatmentFilter: '',
        dateFilter: '',
        statusFilter: '',
        
        treatmentOpen: false,
        dateOpen: false,
        statusOpen: false,

        get filteredPatients() {
            return this.patients.filter(p => {
                return (
                    (this.treatmentFilter === '' || p.treatment === this.treatmentFilter) &&
                    (this.statusFilter === '' || p.status === this.statusFilter) &&
                    (this.dateFilter === '' || p.date.startsWith(this.dateFilter))
                );
            });
        }
    }">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold">Patient Status</h2>

        <button class="bg-[#EBDDAF] text-[#806B3F] text-sm font-light px-4 py-1 rounded-full hover:bg-[#e2d193] transition">
            View All
        </button>
    </div>

    {{-- TABLE --}}
    <table class="w-full border-collapse text-sm min-w-[600px]">
        <thead>
            <tr class="bg-gray-100/50">

                <th class="py-2 px-3 text-left font-light rounded-l-lg">No</th>
                <th class="py-2 px-3 text-left font-light">Patient</th>

                {{-- TREATMENT FILTER --}}
                <th class="relative py-2 px-3 text-left font-light">
                    <div class="flex items-center gap-1 cursor-pointer" @click="treatmentOpen=!treatmentOpen">
                        Treatment
                        <div class="flex flex-col leading-[0.5] ml-1 opacity-60">
                            <iconify-icon icon='tabler:caret-up' class="text-[10px]"></iconify-icon>
                            <iconify-icon icon='tabler:caret-down' class="text-[10px] -mt-1"></iconify-icon>
                        </div>
                    </div>

                    <div x-show="treatmentOpen" @click.away="treatmentOpen=false"
                        class="absolute left-0 mt-1 bg-white shadow-lg border rounded-lg z-50 w-44 text-sm">

                        <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                            @click="treatmentFilter=''; treatmentOpen=false">All</button>

                        <template x-for="item in [...new Set(patients.map(p=>p.treatment))]">
                            <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                                @click="treatmentFilter=item; treatmentOpen=false"
                                x-text="item"></button>
                        </template>
                    </div>
                </th>

                {{-- DATE FILTER --}}
                <th class="relative py-2 px-3 text-left font-light">
                    <div class="flex items-center gap-1 cursor-pointer" @click="dateOpen=!dateOpen">
                        Date & Time
                        <div class="flex flex-col leading-[0.5] ml-1 opacity-60">
                            <iconify-icon icon='tabler:caret-up' class="text-[10px]"></iconify-icon>
                            <iconify-icon icon='tabler:caret-down' class="text-[10px] -mt-1"></iconify-icon>
                        </div>
                    </div>

                    <div x-show="dateOpen" @click.away="dateOpen=false"
                        class="absolute left-0 mt-1 bg-white shadow-lg border rounded-lg z-50 w-36 text-sm">

                        <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                            @click="dateFilter=''; dateOpen=false">All</button>

                        <template x-for="dt in [...new Set(patients.map(p=>p.date.split(' ')[0]))]">
                            <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                                @click="dateFilter=dt; dateOpen=false"
                                x-text="dt"></button>
                        </template>
                    </div>
                </th>

                {{-- STATUS FILTER --}}
                <th class="relative py-2 px-3 text-left font-light rounded-r-lg">
                    <div class="flex items-center gap-1 cursor-pointer" @click="statusOpen=!statusOpen">
                        Status
                        <div class="flex flex-col leading-[0.5] ml-1 opacity-60">
                            <iconify-icon icon='tabler:caret-up' class="text-[10px]"></iconify-icon>
                            <iconify-icon icon='tabler:caret-down' class="text-[10px] -mt-1"></iconify-icon>
                        </div>
                    </div>

                    <div x-show="statusOpen" @click.away="statusOpen=false"
                        class="absolute left-0 mt-1 bg-white shadow-lg border rounded-lg z-50 w-36 text-sm">

                        <template x-for="st in ['All','Completed','In Progress','Scheduled','Cancelled']">
                            <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                                @click="statusFilter=(st==='All'?'':st); statusOpen=false"
                                x-text="st"></button>
                        </template>
                    </div>
                </th>

            </tr>
        </thead>

        {{-- BODY --}}
        <tbody class="divide-y">
            <template x-for="(patient,index) in filteredPatients" :key="patient.id">
                <tr class="bg-gray-50/30">

                    <td class="py-2 px-3" x-text="index+1"></td>
                    <td class="py-2 px-3" x-text="patient.name"></td>
                    <td class="py-2 px-3" x-text="patient.treatment"></td>
                    <td class="py-2 px-3" x-text="patient.date"></td>

                    {{-- CUSTOM DROPDOWN STATUS --}}
                    <td class="py-2 px-3">
                        <div class="relative" x-data="{ openStatus:false }">

                            <button @click="openStatus = !openStatus"
                                class="w-full flex items-center justify-between px-2 py-1 text-xs font-semibold rounded-lg border border-gray-200 transition"
                                :class="{
                                    'bg-green-100 text-green-700': patient.status==='Completed',
                                    'bg-blue-100 text-blue-700': patient.status==='In Progress',
                                    'bg-gray-100 text-gray-700': patient.status==='Scheduled',
                                    'bg-red-100 text-red-700': patient.status==='Cancelled'
                                }">
                                <span x-text="patient.status"></span>
                                <iconify-icon icon="tabler:chevron-down" class="text-xs ml-1"></iconify-icon>
                            </button>

                            <div x-show="openStatus" @click.away="openStatus=false"
                                class="absolute mt-1 w-full bg-white shadow-lg border rounded-lg z-50 text-xs">

                                <template x-for="st in ['Completed','In Progress','Scheduled','Cancelled']">
                                    <button
                                        class="block w-full text-left px-3 py-2 hover:bg-gray-100"
                                        @click="patient.status = st; openStatus=false"
                                        x-text="st"></button>
                                </template>

                            </div>

                        </div>
                    </td>

                </tr>
            </template>
        </tbody>
    </table>

</div>
        </div>

       {{-- === KANAN: Calendar + Jadwal Treatment === --}}
<div class="w-full lg:w-[34%] flex flex-col gap-6"
     x-data="calendarComponent()"
     x-init="initCalendar()">

    {{-- Calendar --}}
    <div class="bg-white p-4 md:p-6 rounded-xl shadow">

        {{-- Header Dropdown Bulan & Tahun --}}
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">

                {{-- Dropdown Bulan --}}
                <div class="relative w-auto">
                    <select x-model.number="currentMonth"
                        @change="updateCalendar()"
                        class="appearance-none bg-transparent border-none text-base font-bold pr-10 pl-1 cursor-pointer outline-none focus:ring-0">

                        <template x-for="(month, index) in monthNames" :key="index">
                            <option :value="index" x-text="month"></option>
                        </template>

                    </select>

                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                    </span>
                </div>

                {{-- ============================== --}}
                {{-- DROPDOWN TAHUN (SEARCH + SCROLL) --}}
                {{-- ============================== --}}
                <div class="relative w-32"
                     x-data="yearDropdown()"
                     x-init="init()"
                     @click.outside="close()">

                    {{-- Button Tahun --}}
                    <button @click="toggle()"
                        class="appearance-none bg-transparent border-none text-base font-bold pr-8 pl-1 w-full flex justify-between items-center cursor-pointer">
                        
                        <span x-text="currentYear"></span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-500"></i>
                    </button>

                    {{-- Dropdown Panel --}}
                    <div x-show="open"
                        class="absolute left-0 mt-1 bg-white shadow-lg border rounded-lg z-50 w-full">

                        {{-- Search Box --}}
                        <div class="p-2 border-b">
                            <input placeholder="Search year..."
                                   x-model="search"
                                   @input="doSearch()"
                                   class="w-full border rounded px-2 py-1 text-sm focus:outline-none">
                        </div>

                        {{-- Scrollable List --}}
                        <div class="h-48 overflow-y-auto" @scroll="onScroll($event)">
                            <template x-for="year in filtered" :key="year">
                                <div @click="selectYear(year)"
                                    class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                                    :class="year === currentYear ? 'bg-gray-200 font-semibold' : ''"
                                    x-text="year"></div>
                            </template>
                        </div>

                    </div>
                </div>

            </div>
        </div>


        {{-- Hari --}}
        <div class="mt-3 text-xs text-gray-400">
            
            <div class="grid grid-cols-7 text-center mb-1">
                <template x-for="day in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']">
                    <span x-text="day" class="font-semibold"></span>
                </template>
            </div>

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

    {{-- === Jadwal Treatment === --}}
    <div class="bg-white p-4 md:p-6 rounded-xl shadow">

        <div class="flex justify-between items-center mb-3">
            <div>
                <h3 class="text-sm font-bold text-[#000000]">Jadwal Treatment</h3>
                <p class="text-xs text-gray-500"
                   x-text="selectedDate + ' ' + monthNames[currentMonth] + ' ' + currentYear"></p>
            </div>

            <button class="text-[#806B3F] text-lg hover:text-[#A18F5E]">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>

        <ul class="divide-y divide-black text-xs md:text-sm">
    <li class="flex items-center justify-between py-2">
        <div class="flex items-center gap-2">
            <input type="checkbox" checked 
                   class="accent-[#8CF69C] w-4 h-4">
            <span class="text-gray-700">Susi Susanti</span>
        </div>
        <span class="text-gray-500">09.00 - 10.30</span>
    </li>

    <li class="flex items-center justify-between py-2">
        <div class="flex items-center gap-2">
            <input type="checkbox" class="accent-[#8CF69C] w-4 h-4">
            <span class="text-gray-700">Aliyah Runa</span>
        </div>
        <span class="text-gray-500">11.00 - 12.30</span>
    </li>

    <li class="flex items-center justify-between py-2">
        <div class="flex items-center gap-2">
            <input type="checkbox" class="accent-[#8CF69C] w-4 h-4">
            <span class="text-gray-700">Asep</span>
        </div>
        <span class="text-gray-500">13.20 - 15.00</span>
    </li>
</ul>
    </div>

</div>

{{-- ================================ --}}
{{-- SCRIPT KOMPONEN CALENDAR --}}
{{-- ================================ --}}
<script>
function calendarComponent() {
    return {
        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),
        selectedDate: new Date().getDate(),

        daysInMonth: [],
        firstDayOfMonth: 0,

        monthNames: [
            'January','February','March','April','May','June',
            'July','August','September','October','November','December'
        ],

        initCalendar() {
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


{{-- ========================================= --}}
{{-- SCRIPT DROPDOWN TAHUN (SEARCH + SCROLL) --}}
{{-- ========================================= --}}
<script>
function yearDropdown() {
    return {
        open: false,
        search: "",

        totalYears: [],
        shownYears: [],
        filtered: [],

        currentYear: new Date().getFullYear(),

        start: new Date().getFullYear() - 50,
        end: new Date().getFullYear() + 50,

        init() {
            this.generateTotalYears();
            this.loadShownYears();
            this.filtered = this.shownYears;
        },

        generateTotalYears() {
            this.totalYears = [];
            for (let y = 1900; y <= 3000; y++) {
                this.totalYears.push(y);
            }
        },

        loadShownYears() {
            this.shownYears = this.totalYears.filter(y => y >= this.start && y <= this.end);
        },

        doSearch() {
            const keyword = this.search.trim();

            this.filtered = keyword === ""
                ? this.shownYears
                : this.totalYears.filter(y => y.toString().includes(keyword));
        },

        onScroll(e) {
            const box = e.target;

            if (box.scrollTop + box.clientHeight >= box.scrollHeight - 10) {
                this.end += 30;
                this.loadShownYears();
                this.doSearch();
            }

            if (box.scrollTop <= 10) {
                this.start -= 30;
                this.loadShownYears();
                this.doSearch();
            }
        },

        toggle() {
            this.open = !this.open;
        },

        close() {
            this.open = false;
        },

        selectYear(year) {
            this.currentYear = year;
            this.open = false;

            const calendar = document.querySelector('[x-data="calendarComponent()"]')?.__x?.$data;
            if (calendar) {
                calendar.currentYear = year;
                calendar.updateCalendar();
            }
        }
    };
}
</script>



    </div>

    <div class="bg-white p-2 rounded-xl shadow w-full max-w-[600px] overflow-x-auto">

    <div class="pl-3 pr-3">
        
        <h2 class="text-lg font-semibold text-black mb-3 mt-1">
            Most Popular Treatments
        </h2>

        <div class="space-y-3">

            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-3">
                    <span class="bg-[#EED892]/50 text-[#806B3F] text-sm font-semibold px-3 py-1 rounded-md">#1</span>
                    <span class="text-gray-900 font-medium">Facial Rejuvenation</span>
                </div>
                <span class="text-sm text-[#808080] pr-6">423 reservations</span>
            </div>
            <hr class="border-gray-200">

            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-3">
                    <span class="bg-[#EED892]/50 text-[#806B3F] text-sm font-semibold px-3 py-1 rounded-md">#2</span>
                    <span class="text-gray-900 font-medium">Laser Hair Removal</span>
                </div>
                <span class="text-sm text-[#808080] pr-6">354 reservations</span>
            </div>
            <hr class="border-gray-200">

            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-3">
                    <span class="bg-[#EED892]/50 text-[#806B3F] text-sm font-semibold px-3 py-1 rounded-md">#3</span>
                    <span class="text-gray-900 font-medium">Botox Injection</span>
                </div>
                <span class="text-sm text-[#808080] pr-6">177 reservations</span>
            </div>
            <hr class="border-gray-200">

            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-3">
                    <span class="bg-[#EED892]/50 text-[#806B3F] text-sm font-semibold px-3 py-1 rounded-md">#4</span>
                    <span class="text-gray-900 font-medium">Chemical Peels</span>
                </div>
                <span class="text-sm text-[#808080] pr-6">100 reservations</span>
            </div>

        </div>
    </div>

</div>



@endsection
