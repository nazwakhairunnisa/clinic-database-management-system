@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Treatment')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif]">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">

        {{-- Search + Buttons sejajar --}}
        <div class="flex w-full items-center justify-between gap-3">
            
            {{-- Search Bar --}}
            <div class="relative flex-grow">
                <input type="text" placeholder="Search treatment"
                    class="border border-white rounded-full pl-10 pr-4 py-2 w-full sm:w-60 focus:ring-2 focus:ring-[#EED892] focus:outline-none text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
            </div>

            {{-- Buttons di kanan --}}
            <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                {{-- Export --}}
                <button
                    class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-file-export text-base sm:mr-2"></i>
                    <span class="hidden sm:inline">Export</span>
                </button>

                {{-- Add Treatment --}}
                <a href="{{ route('owner.treatment.add') }}"
                    class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-plus text-base sm:mr-2"></i>
                    <span class="hidden sm:inline">Add Treatment</span>
                </a>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto transition-all duration-300">
        <table class="min-w-[800px] w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100/60 text-gray-700 font-normal">
                    <th class="px-4 py-3 rounded-l-lg border-b border-gray-300">No</th>
                    <th class="px-4 py-3 border-b border-gray-300">ID Treatment</th>
                    <th class="px-4 py-3 border-b border-gray-300">Name</th>
                    <th class="px-4 py-3 border-b border-gray-300">Harga</th>
                    <th class="px-4 py-3 border-b border-gray-300">Deskripsi</th>
                    <th class="px-4 py-3 border-b border-gray-300">Image</th>
                    <th class="px-4 py-3 rounded-r-lg border-b border-gray-300">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @foreach([
                    ['no'=>1,'id'=>'T001','name'=>'Facial Rejuvenation','harga'=>'Rp 350.000','desc'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit.','img'=>'image1.jpg'],
                    ['no'=>2,'id'=>'T002','name'=>'Laser Hair Removal','harga'=>'Rp 850.000','desc'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit.','img'=>'image2.jpg'],
                    ['no'=>3,'id'=>'T003','name'=>'Botox Injection','harga'=>'Rp 520.000','desc'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit.','img'=>'image3.jpg'],
                    ['no'=>4,'id'=>'T004','name'=>'Chemical Peels','harga'=>'Rp 350.000','desc'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit.','img'=>'image4.jpg'],
                ] as $row)
                <tr class="bg-white hover:bg-gray-50 transition-all duration-200 border-b border-gray-200">
                    <td class="px-4 py-3">{{ $row['no'] }}</td>
                    <td class="px-4 py-3">{{ $row['id'] }}</td>
                    <td class="px-4 py-3">{{ $row['name'] }}</td>
                    <td class="px-4 py-3">{{ $row['harga'] }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $row['desc'] }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $row['img'] }}</td>

                    {{-- ACTION BUTTONS --}}
                    <td class="px-4 py-3 flex space-x-3">
                        {{-- EDIT --}}
                        <a href="{{ route('owner.treatment.edit') }}" 
                           class="text-gray-700 hover:text-black transition-all duration-200">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        {{-- DELETE --}}
                        <button class="text-gray-700 hover:text-black transition-all duration-200"
                                onclick="confirm('Apakah kamu yakin ingin menghapus treatment ini?')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
