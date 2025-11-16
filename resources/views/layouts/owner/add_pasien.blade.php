@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Pasien')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto'] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-8 sm:pt-12">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6 sm:p-8 transition-all duration-300">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-black">Add Patient</h2>
            <a href="{{ route('owner.pasien') }}" class="text-gray-400 hover:text-gray-600 transition">
    <i class="fa-solid fa-xmark text-xl"></i>
</a>
        </div>

        <form class="space-y-5">
            {{-- Row 1 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">First Name <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm">
                </div>
            </div>

            {{-- Row 2 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Email <span class="text-red-500">*</span></label>
                    <input type="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Phone <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm">
                </div>
            </div>

            {{-- Row 3 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Date of Birth</label>
                    <input type="date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Gender</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm">
                        <option value="">- Select -</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </div>
            </div>

            {{-- Row 4 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Appointment Date <span class="text-red-500">*</span></label>
                    <input type="date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Time</label>
                    <input type="time" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm">
                </div>
            </div>

            {{-- Row 5 --}}
            <div>
                <label class="text-sm font-medium text-gray-600">Treatment <span class="text-red-500">*</span></label>
                <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm">
                    <option value="">- Select -</option>
                    <option>Facial Rejuvenation</option>
                    <option>Laser Hair Removal</option>
                    <option>Botox Injection</option>
                    <option>Chemical Peels</option>
                </select>
            </div>

            {{-- Submit --}}
            <div class="pt-2">
                <button type="submit" 
                    class="bg-[#2F9CCA] text-white px-6 py-2 rounded-lg hover:bg-[#1788b4] transition-all duration-200">
                    Add
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
