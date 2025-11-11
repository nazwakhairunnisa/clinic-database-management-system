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
            <form method="GET" action="{{ route('owner.treatment') }}" class="relative flex-grow">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search treatment"
                    class="border border-white rounded-full pl-10 pr-4 py-2 w-full sm:w-60 focus:ring-2 focus:ring-[#EED892] focus:outline-none text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
            </form>

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
                <tr class="bg-gray-100/60 text-gray-700 font-normal">
                    <th class="px-4 py-3 rounded-l-lg border-b border-gray-300">No</th>
                    <th class="px-4 py-3 border-b border-gray-300">ID Treatment</th>
                    <th class="px-4 py-3 border-b border-gray-300">Name</th>
                    <th class="px-4 py-3 border-b border-gray-300">Harga</th>
                    <th class="px-4 py-3 border-b border-gray-300">Durasi</th>
                    <th class="px-4 py-3 border-b border-gray-300">Deskripsi</th>
                    <th class="px-4 py-3 border-b border-gray-300">Image</th>
                    <th class="px-4 py-3 rounded-r-lg border-b border-gray-300">Action</th>
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

                    {{-- ACTION BUTTONS --}}
                    <td class="px-4 py-3 flex space-x-3">
                        {{-- EDIT --}}
                        <a href="{{ route('owner.treatment.edit', $treatment->id_treatment) }}" 
                           class="text-gray-700 hover:text-black transition-all duration-200"
                           title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        {{-- DELETE --}}
                        <form action="{{ route('owner.treatment.destroy', $treatment->id_treatment) }}" 
                              method="POST" 
                              onsubmit="return confirm('Apakah kamu yakin ingin menghapus treatment ini?')"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-gray-700 hover:text-red-600 transition-all duration-200"
                                    title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
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