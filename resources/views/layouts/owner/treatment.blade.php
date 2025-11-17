@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Treatment')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif]">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">

        {{-- Search + Buttons sejajar --}}
        <div class="flex w-full items-center justify-between gap-3">
            
            {{-- Search --}}
            <div class="relative flex-grow">
                <input type="text" placeholder="Search treatment"
                    class="border border-white rounded-full pl-10 pr-4 py-2 w-full sm:w-60
                    focus:ring-2 focus:ring-[#EED892] focus:outline-none text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
            </div>

             {{-- Buttons --}}
            <div class="flex space-x-2 sm:space-x-3 shrink-0">

                {{-- Export --}}
                <button
                    class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-full
                    hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    
                    <iconify-icon icon="bx:export" class="text-xl sm:mr-2"></iconify-icon>
                    <span class="hidden sm:inline">Export</span>
                </button>

                {{-- Add Treatment --}}
                <a href="{{ route('owner.treatment.add') }}"  
                    class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-full
                    hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    
                    <iconify-icon icon="material-symbols:add-rounded" class="text-xl sm:mr-2"></iconify-icon>
                    <span class="hidden sm:inline">Add Treatment</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto transition-all duration-300">
        <table class="min-w-[800px] w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100/60 text-gray-700">
                    <th class="px-4 py-3 rounded-l-lg border-b border-gray-300 font-light">No</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">ID Treatment</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Name</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Harga</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Durasi</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Deskripsi</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Image</th>
                    <th class="px-4 py-3 rounded-r-lg border-b border-gray-300 font-light">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                
                @forelse($treatments as $index => $treatment)
                <tr class="bg-white hover:bg-gray-50 transition-all duration-200 border-b border-gray-200">
                    <td class="px-4 py-3">{{ $treatments->firstItem() + $index }}</td>
                    <td class="px-4 py-3">T{{ str_pad($treatment->id_treatment, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-4 py-3">{{ $treatment->nama_treatment }}</td>
                    <td class="px-4 py-3">{{ $treatment->formatted_harga }}</td>
                    <td class="px-4 py-3">{{ $treatment->durasi }} menit</td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ Str::limit($treatment->deskripsi, 50) }}
                    </td>
                    <td class="px-4 py-3">
                        @if($treatment->foto_treatment)
                            <img src="{{ $treatment->foto_url }}" alt="{{ $treatment->nama_treatment }}" 
                                 class="w-16 h-16 object-cover rounded">
                        @else
                            <span class="text-gray-400 text-xs">No image</span>
                        @endif
                    </td>


                    {{-- Action --}}
                        <td class="px-4 py-3 flex space-x-3">
                            <a href="{{ route('owner.pasien.editRekam') }}"
                                class="text-gray-700 hover:text-black transition-all duration-200">
                                <iconify-icon icon="mingcute:edit-line" class="text-lg"></iconify-icon>
                            </a>

                            <button class="text-gray-700 hover:text-black transition-all duration-200">
                                <iconify-icon icon="material-symbols:delete-outline" class="text-lg"></iconify-icon>
                            </button>
                        </td>

                    {{-- ACTION BUTTONS --}}
                    <td class="px-4 py-3 flex space-x-3">
                        {{-- EDIT --}}
                        <a href="{{ route('owner.treatment.edit', $treatment->id_treatment) }}"
                          class="text-gray-700 hover:text-black transition-all duration-200"> 
                            <iconify-icon icon="mingcute:edit-line" class="text-lg"></iconify-icon>
                        </a>

                        {{-- DELETE --}}
                        <button class="text-gray-700 hover:text-black transition-all duration-200">
                                <iconify-icon icon="material-symbols:delete-outline" class="text-lg"></iconify-icon>
                            </button>
                        </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                        Belum ada data treatment
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $treatments->links() }}
        </div>
    </div>
</div>
@endsection