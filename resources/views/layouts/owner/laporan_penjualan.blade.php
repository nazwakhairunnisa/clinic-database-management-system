@extends('layouts.owner.app')

@section('pageTitle', 'Laporan Penjualan')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif]">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">
        <div class="flex w-full items-center justify-between gap-3">

            {{-- Search Bar --}}
            <div class="relative flex-grow">
                <input type="text" placeholder="Search Penjualan"
                    class="border border-white rounded-full pl-10 pr-4 py-2 w-full sm:w-60 focus:ring-2 focus:ring-[#EED892] focus:outline-none text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
            </div>

            {{-- Export Button --}}
            <button
                class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                <i class="fa-solid fa-file-export text-base sm:mr-2"></i>
                <span class="hidden sm:inline">Export</span>
            </button>
        </div>
    </div>

    {{-- DASHBOARD CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        {{-- Total Income --}}
        <div class="bg-white rounded-xl shadow p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 text-sm font-medium">Total Income</h3>
                <div class="bg-[#3749A7]/10 text-[#3749A7] p-2 rounded-lg">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-[#3749A7]">$125,150</p>
            <p class="text-sm text-green-500 mt-1">▲ 5.62% from last month</p>
        </div>

        {{-- Total Expenses --}}
        <div class="bg-white rounded-xl shadow p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 text-sm font-medium">Total Expenses</h3>
                <div class="bg-green-100 text-green-600 p-2 rounded-lg">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-green-600">$91,800</p>
            <p class="text-sm text-green-500 mt-1">▲ 11.4% from last month</p>
        </div>

        {{-- Net Profit --}}
        <div class="bg-white rounded-xl shadow p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 text-sm font-medium">Net Profit</h3>
                <div class="bg-yellow-100 text-yellow-600 p-2 rounded-lg">
                    <i class="fa-solid fa-coins"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-yellow-600">$91,800</p>
            <p class="text-sm text-green-500 mt-1">▲ 8.52% from last month</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto">
        <table class="min-w-[800px] w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100/60 text-gray-700 font-semibold">
                    <th class="px-4 py-3">Income</th>
                    <th class="px-4 py-3">Jan 2025</th>
                    <th class="px-4 py-3">Feb 2025</th>
                    <th class="px-4 py-3">Mar 2025</th>
                    <th class="px-4 py-3">Apr 2025</th>
                    <th class="px-4 py-3">May 2025</th>
                    <th class="px-4 py-3">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y text-gray-700">
                {{-- Income Section --}}
                <tr class="bg-gray-50">
                    <td class="px-4 py-3 font-semibold">Consultation Fees</td>
                    <td class="px-4 py-3">$25,750</td>
                    <td class="px-4 py-3">$25,750</td>
                    <td class="px-4 py-3">$25,750</td>
                    <td class="px-4 py-3">$25,750</td>
                    <td class="px-4 py-3">$25,750</td>
                    <td class="px-4 py-3 font-bold">$25,750</td>
                </tr>
                <tr>
                    <td class="px-4 py-3 font-semibold">Lab Revenue</td>
                    <td class="px-4 py-3">$50,125</td>
                    <td class="px-4 py-3">$50,125</td>
                    <td class="px-4 py-3">$50,125</td>
                    <td class="px-4 py-3">$50,125</td>
                    <td class="px-4 py-3">$50,125</td>
                    <td class="px-4 py-3 font-bold">$50,125</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-3 font-semibold">Pharmacy Sales</td>
                    <td class="px-4 py-3">$75,900</td>
                    <td class="px-4 py-3">$75,900</td>
                    <td class="px-4 py-3">$75,900</td>
                    <td class="px-4 py-3">$75,900</td>
                    <td class="px-4 py-3">$75,900</td>
                    <td class="px-4 py-3 font-bold">$75,900</td>
                </tr>
                <tr class="bg-gray-100 font-bold">
                    <td class="px-4 py-3">Gross Profit</td>
                    <td class="px-4 py-3">$151,775</td>
                    <td class="px-4 py-3">$151,775</td>
                    <td class="px-4 py-3">$151,775</td>
                    <td class="px-4 py-3">$151,775</td>
                    <td class="px-4 py-3">$151,775</td>
                    <td class="px-4 py-3">$151,775</td>
                </tr>

                {{-- Expenses Section --}}
                <tr class="bg-gray-50 font-semibold">
                    <td class="px-4 py-3">Doctor Payouts</td>
                    <td class="px-4 py-3">$25,750</td>
                    <td class="px-4 py-3">$25,750</td>
                    <td class="px-4 py-3">$25,750</td>
                    <td class="px-4 py-3">$25,750</td>
                    <td class="px-4 py-3">$25,750</td>
                    <td class="px-4 py-3 font-bold">$25,750</td>
                </tr>
                <tr>
                    <td class="px-4 py-3">Staff Salaries</td>
                    <td class="px-4 py-3">$50,125</td>
                    <td class="px-4 py-3">$50,125</td>
                    <td class="px-4 py-3">$50,125</td>
                    <td class="px-4 py-3">$50,125</td>
                    <td class="px-4 py-3">$50,125</td>
                    <td class="px-4 py-3 font-bold">$50,125</td>
                </tr>
                <tr>
                    <td class="px-4 py-3">Rent & Utilities</td>
                    <td class="px-4 py-3">$75,900</td>
                    <td class="px-4 py-3">$75,900</td>
                    <td class="px-4 py-3">$75,900</td>
                    <td class="px-4 py-3">$75,900</td>
                    <td class="px-4 py-3">$75,900</td>
                    <td class="px-4 py-3 font-bold">$87,650</td>
                </tr>
                <tr class="bg-gray-100 font-bold">
                    <td class="px-4 py-3">Total Expense</td>
                    <td class="px-4 py-3">$99,999</td>
                    <td class="px-4 py-3">$99,999</td>
                    <td class="px-4 py-3">$99,999</td>
                    <td class="px-4 py-3">$99,999</td>
                    <td class="px-4 py-3">$99,999</td>
                    <td class="px-4 py-3">$151,775</td>
                </tr>
                <tr class="bg-yellow-50 font-bold">
                    <td class="px-4 py-3">Net Income</td>
                    <td class="px-4 py-3">$2,69,276</td>
                    <td class="px-4 py-3">$2,75,638</td>
                    <td class="px-4 py-3">$2,51,629</td>
                    <td class="px-4 py-3">$7,96,543</td>
                    <td class="px-4 py-3">$2,69,276</td>
                    <td class="px-4 py-3">$2,75,638</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
