@extends('layouts.owner.app') 

@section('pageTitle', 'Laporan Penjualan')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] px-4 sm:px-6 lg:px-10 py-6">

    {{-- ========================= --}}
    {{-- SEARCH + EXPORT --}}
    {{-- ========================= --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">

        {{-- Search --}}
        <div class="relative w-full sm:w-72">
            <input type="text" placeholder="Search Penjualan"
                class="border border-gray-300 rounded-full pl-10 pr-4 py-2 w-full focus:ring-2 focus:ring-[#EED892] outline-none">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
        </div>

         {{-- Export --}}
            <button
                class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-full
                hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">

                <iconify-icon icon="bx:export" class="text-xl mr-2"></iconify-icon>
                <span class="inline">Export</span>

            </button>
    </div>
    {{-- ========================= --}}
    {{-- SUMMARY CARDS --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

        {{-- CARD 1 --}}
        <div class="bg-white shadow rounded-xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">

                <div>
                    <p class="text-gray-500 text-sm">Total Income</p>
                    <p class="font-semibold text-xl text-[#0A1A2A]">$125,150</p>

                    {{-- LAST MONTH BOX --}}
                    <div class="bg-gray-100 rounded-lg px-3 py-2 mt-3 inline-flex items-center gap-1">
                        <span class="text-green-600 text-sm">↑</span>
                        <span class="text-green-600 text-sm font-semibold">5.62%</span>
                        <span class="text-gray-500 text-sm">from last month</span>
                    </div>
                </div>

                <img src="{{ asset('images/IconContainer.png') }}" 
                     class="w-14 h-14 object-contain ml-4">
            </div>
        </div>


        {{-- CARD 2 --}}
        <div class="bg-white shadow rounded-xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">

                <div>
                    <p class="text-gray-500 text-sm">Total Expenses</p>
                    <p class="font-semibold text-xl text-[#0A1A2A]">$91,800</p>

                    <div class="bg-gray-100 rounded-lg px-3 py-2 mt-3 inline-flex items-center gap-1">
                        <span class="text-green-600 text-sm">↑</span>
                        <span class="text-green-600 text-sm font-semibold">11.4%</span>
                        <span class="text-gray-500 text-sm">from last month</span>
                    </div>
                </div>

                <img src="{{ asset('images/IconContainer2.png') }}" 
                     class="w-14 h-14 object-contain ml-4">
            </div>
        </div>


        {{-- CARD 3 --}}
        <div class="bg-white shadow rounded-xl p-5 border border-gray-200">
            <div class="flex items-start justify-between">

                <div>
                    <p class="text-gray-500 text-sm">Net Profit</p>
                    <p class="font-semibold text-xl text-[#0A1A2A]">$91,800</p>

                    <div class="bg-gray-100 rounded-lg px-3 py-2 mt-3 inline-flex items-center gap-1">
                        <span class="text-green-600 text-sm">↑</span>
                        <span class="text-green-600 text-sm font-semibold">8.52%</span>
                        <span class="text-gray-500 text-sm">from last month</span>
                    </div>
                </div>

                <img src="{{ asset('images/IconContainer3.png') }}" 
                     class="w-14 h-14 object-contain ml-4">
            </div>
        </div>

    </div>

        <div class="bg-white rounded-2xl shadow p-4 overflow-x-auto border border-gray-200">

    <table class="w-full min-w-[900px] text-sm border-collapse">

        {{-- ========================= --}}
        {{-- HEADER BULAN --}}
        {{-- ========================= --}}
        <thead>
            <tr>
                <th class="px-4 py-3 font-semibold text-left"></th>
                <th class="px-4 py-3 font-semibold text-center">Jan 2025</th>
                <th class="px-4 py-3 font-semibold text-center">Feb 2025</th>
                <th class="px-4 py-3 font-semibold text-center">Mar 2025</th>
                <th class="px-4 py-3 font-semibold text-center">Apr 2025</th>
                <th class="px-4 py-3 font-semibold text-center">May 2025</th>
                <th class="px-4 py-3 font-semibold text-center">Total</th>
            </tr>
        </thead>

        <tbody class="text-gray-700">

            {{-- ========================= --}}
            {{-- LABEL INCOME --}}
            {{-- ========================= --}}
            <tr>
                <td colspan="7" class="px-4 py-4 font-bold text-gray-900 text-lg">
                    Income
                </td>
            </tr>

            {{-- Consultation --}}
            <tr class="border-b">
                <td class="px-4 py-3">Consultation Fees</td>
                <td class="px-4 py-3 text-center">$25,750</td>
                <td class="px-4 py-3 text-center">$25,750</td>
                <td class="px-4 py-3 text-center">$25,750</td>
                <td class="px-4 py-3 text-center">$25,750</td>
                <td class="px-4 py-3 text-center">$25,750</td>
                <td class="px-4 py-3 text-center font-semibold">$25,750</td>
            </tr>

            {{-- Lab Revenue --}}
            <tr class="border-b">
                <td class="px-4 py-3">Lab Revenue</td>
                <td class="px-4 py-3 text-center">$50,125</td>
                <td class="px-4 py-3 text-center">$50,125</td>
                <td class="px-4 py-3 text-center">$50,125</td>
                <td class="px-4 py-3 text-center">$50,125</td>
                <td class="px-4 py-3 text-center">$50,125</td>
                <td class="px-4 py-3 text-center font-semibold">$50,125</td>
            </tr>

            {{-- Pharmacy --}}
            <tr class="border-b">
                <td class="px-4 py-3">Pharmacy Sales</td>
                <td class="px-4 py-3 text-center">$75,900</td>
                <td class="px-4 py-3 text-center">$75,900</td>
                <td class="px-4 py-3 text-center">$75,900</td>
                <td class="px-4 py-3 text-center">$75,900</td>
                <td class="px-4 py-3 text-center">$75,900</td>
                <td class="px-4 py-3 text-center font-semibold">$75,900</td>
            </tr>

            {{-- ========================= --}}
            {{-- GROSS PROFIT --}}
            {{-- ========================= --}}
            <tr class="border-b font-semibold">
                <td class="px-4 py-3">Gross Profit</td>
                <td class="px-4 py-3 text-center">$151,775</td>
                <td class="px-4 py-3 text-center">$151,775</td>
                <td class="px-4 py-3 text-center">$151,775</td>
                <td class="px-4 py-3 text-center">$151,775</td>
                <td class="px-4 py-3 text-center">$151,775</td>
                <td class="px-4 py-3 text-center">$151,775</td>
            </tr>

            {{-- ========================= --}}
            {{-- LABEL EXPENSE --}}
            {{-- ========================= --}}
            <tr>
                <td colspan="7" class="px-4 py-4 font-bold text-gray-900 text-lg">
                    Expense
                </td>
            </tr>

    

                {{-- Expenses --}}
                <tr class="border-b">
                    <td class="px-4 py-3">Doctor Payouts</td>
                    <td class="px-4 py-3 text-center ">$25,750</td>
                    <td class="px-4 py-3 text-center">$25,750</td>
                    <td class="px-4 py-3 text-center">$25,750</td>
                    <td class="px-4 py-3 text-center">$25,750</td>
                    <td class="px-4 py-3 text-center">$25,750</td>
                    <td class="px-4 py-3 text-center font-semibold">$25,750</td>
                </tr>

                <tr class="border-b">
                    <td class="px-4 py-3 ">Staff Salaries</td>
                    <td class="px-4 py-3 text-center">$50,125</td>
                    <td class="px-4 py-3 text-center">$50,125</td>
                    <td class="px-4 py-3 text-center">$50,125</td>
                    <td class="px-4 py-3 text-center">$50,125</td>
                    <td class="px-4 py-3 text-center">$50,125</td>
                    <td class="px-4 py-3 text-center font-semibold">$50,125</td>
                </tr>

                <tr class="border-b">
                    <td class="px-4 py-3">Rent & Utilities</td>
                    <td class="px-4 py-3 text-center">$75,900</td>
                    <td class="px-4 py-3 text-center">$75,900</td>
                    <td class="px-4 py-3 text-center">$75,900</td>
                    <td class="px-4 py-3 text-center">$75,900</td>
                    <td class="px-4 py-3 text-center">$75,900</td>
                    <td class="px-4 py-3 text-center font-semibold">$87,650</td>
                </tr>

                {{-- TOTAL EXPENSE --}}
                <tr class="border-b font-semibold">
                    <td class="px-4 py-3">Total Expense</td>
                    <td class="px-4 py-3 text-center">$99,999</td>
                    <td class="px-4 py-3 text-center">$99,999</td>
                    <td class="px-4 py-3 text-center">$99,999</td>
                    <td class="px-4 py-3 text-center">$99,999</td>
                    <td class="px-4 py-3 text-center">$99,999</td>
                    <td class="px-4 py-3 text-center">$151,775</td>
                </tr>

                {{-- NET INCOME --}}
                <tr class="font-bold bg-white">
                    <td class="px-4 py-3">Net Income</td>
                    <td class="px-4 py-3 text-center text-black-700">$2,69,276</td>
                    <td class="px-4 py-3 text-center text-black-700">$2,75,638</td>
                    <td class="px-4 py-3 text-center text-black-700">$2,51,629</td>
                    <td class="px-4 py-3 text-center text-black-700">$7,96,543</td>
                    <td class="px-4 py-3 text-center text-black-700">$2,69,276</td>
                    <td class="px-4 py-3 text-center text-BLACK-700">$2,75,638</td>
                </tr>

            </tbody>
        </table>
    </div>

</div>
@endsection
