@extends('layouts.owner.app')  

@section('pageTitle', 'Daftar Pasien')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<div class="font-['Roboto',sans-serif]">

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    {{-- Header Filter --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">

        {{-- Search + Buttons sejajar --}}
        <div class="flex w-full items-center justify-between gap-3">
            
            {{-- Search --}}
            <form method="GET" action="{{ route('owner.pasien.index') }}" class="relative flex-grow">
                <input type="text" name="search" value="{{ request('search') ?? '' }}" 
                    placeholder="Search patient"
                    class="border border-white rounded-full pl-10 pr-4 py-2 w-full sm:w-60
                    focus:ring-2 focus:ring-[#EED892] focus:outline-none text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
            </form>

            {{-- Buttons --}}
            <div class="flex space-x-2 sm:space-x-3 shrink-0">

                {{-- Export --}}
                <a href="{{ route('owner.pasien.export', request()->query()) }}"
                    class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-full
                    hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    
                    <iconify-icon icon="bx:export" class="text-xl sm:mr-2"></iconify-icon>
                    <span class="hidden sm:inline">Export</span>
                </a>

                {{-- Add Patient --}}
                <a href="{{ route('owner.pasien.add') }}"     
                    class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-full
                    hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    
                    <iconify-icon icon="material-symbols:add-rounded" class="text-xl sm:mr-2"></iconify-icon>
                    <span class="hidden sm:inline">Add Patient</span>
                </a>

            </div>

        </div>
    </div>

    {{-- TABLE (desktop-style + scrollable on mobile) --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto">

        @if($pasien->count() > 0)
        <table class="min-w-[1100px] w-full text-sm text-left border-collapse">

            <thead>
                <tr class="bg-gray-100/60 text-gray-700">
                    <th class="px-4 py-3 rounded-l-lg border-b border-gray-300 font-light">No</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">ID-Patient</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Name</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Phone</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Tanggal Lahir</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Age</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Gender</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Rekam Medis</th>
                    <th class="px-4 py-3 rounded-r-lg border-b border-gray-300 font-light">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @foreach($pasien as $index => $row)
                <tr class="bg-white hover:bg-gray-50 transition-all duration-200 border-b border-gray-200">
                    
                    <td class="px-4 py-3">{{ $pasien->firstItem() + $index }}</td>
                    <td class="px-4 py-3 font-medium">P-{{ str_pad($row->id_pasien, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-4 py-3">{{ $row->nama_lengkap }}</td>
                    <td class="px-4 py-3">{{ $row->no_telepon }}</td>
                    <td class="px-4 py-3">{{ $row->tanggal_lahir->format('d M Y') }}</td>
                    <td class="px-4 py-3">{{ $row->usia }} tahun</td>
                    <td class="px-4 py-3">
                        @if($row->jenis_kelamin == 'L')
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">Male</span>
                        @else
                            <span class="px-2 py-1 bg-pink-100 text-pink-700 rounded text-xs">Female</span>
                        @endif
                    </td>

                    {{-- Rekam Medis --}}
                    <td class="px-4 py-3">
                        <a href="{{ route('owner.pasien.show', $row->id_pasien) }}"
                            class="bg-[#F5EAD5] text-black px-3 py-1 rounded-lg text-xs
                            hover:bg-[#EED892] transition-all duration-200">
                            See Details
                        </a>
                    </td>

                    {{-- Action --}}
                    <td class="px-4 py-3 flex space-x-3">
                        {{-- Edit Rekam Medis --}}
                        <a href="{{ route('owner.pasien.editRekam', $row->id_pasien) }}"
                            class="text-gray-700 hover:text-black transition-all duration-200"
                            title="Edit Rekam Medis">
                            <iconify-icon icon="mingcute:edit-line" class="text-lg"></iconify-icon>
                        </a>

                        {{-- DELETE --}}
                        <form action="{{ route('owner.pasien.destroy', $row->id_pasien) }}" 
                              method="POST" 
                              onsubmit="return confirm('Apakah kamu yakin ingin menghapus data pasien ini? Data rekam medis dan histori reservasi akan ikut terhapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="text-gray-700 hover:text-red-600 transition-all duration-200"
                                title="Hapus Pasien">
                                <iconify-icon icon="material-symbols:delete-outline" class="text-lg"></iconify-icon>
                            </button>
                        </form>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $pasien->links() }}
        </div>

        @else
        <div class="text-center py-8 text-gray-500">
            <iconify-icon icon="mdi:account-multiple-outline" class="text-6xl mb-2"></iconify-icon>
            <p>Tidak ada data pasien</p>
            <a href="{{ route('owner.pasien.add') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                Tambah pasien pertama
            </a>
        </div>
        @endif
    </div>

</div>

@endsection