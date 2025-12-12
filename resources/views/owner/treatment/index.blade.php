@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Treatment')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif]">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">

        {{-- Search + Buttons --}}
        <div class="flex w-full items-center justify-between gap-3">
            
            {{-- Search Form --}}
            <form method="GET" action="{{ route('owner.treatment.index') }}" class="relative flex-grow">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search treatment"
                    class="border border-gray-300 rounded-full pl-10 pr-4 py-2 w-full sm:w-60
                    focus:ring-2 focus:ring-[#EED892] focus:outline-none text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
            </form>

            {{-- Buttons --}}
            <div class="flex space-x-2 sm:space-x-3 shrink-0">
                {{-- Export --}}
                <a href="{{ route('owner.treatment.export') }}"
                    class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-full
                    hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    <iconify-icon icon="bx:export" class="text-xl sm:mr-2"></iconify-icon>
                    <span class="hidden sm:inline">Export</span>
                </a>

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
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto transition-all duration-300">
        <table class="min-w-[800px] w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100/60 text-gray-700">
                    <th class="px-4 py-3 rounded-l-lg border-b border-gray-300 font-light">No</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">ID</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Nama Treatment</th>
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
                    
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">
                            T{{ str_pad($treatment->id_treatment, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    
                    <td class="px-4 py-3 font-medium">
                        {{ $treatment->nama_treatment }}
                    </td>
                    
                    <td class="px-4 py-3">
                        <span class="font-semibold text-gray-800">
                            {{ $treatment->formatted_harga }}
                        </span>
                    </td>
                    
                    <td class="px-4 py-3">
                        <span class="text-gray-600">
                            <i class="fa-regular fa-clock text-gray-400 mr-1"></i>
                            {{ $treatment->durasi }} menit
                        </span>
                    </td>
                    
                    <td class="px-4 py-3 text-gray-600 max-w-xs">
                        <div class="truncate" title="{{ $treatment->deskripsi ?? '-' }}">
                            {{ $treatment->deskripsi ? Str::limit($treatment->deskripsi, 50) : '-' }}
                        </div>
                    </td>
                    
                    <td class="px-4 py-3">
                        @if($treatment->foto_treatment)
                            <img src="{{ $treatment->foto_url }}" 
                                 alt="{{ $treatment->nama_treatment }}" 
                                 class="w-16 h-16 object-cover rounded-lg shadow-sm hover:scale-110 transition-transform duration-200 cursor-pointer"
                                 onclick="showImageModal('{{ $treatment->foto_url }}', '{{ $treatment->nama_treatment }}')">
                        @else
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-image text-gray-400 text-xl"></i>
                            </div>
                        @endif
                    </td>

                    {{-- ACTION BUTTONS --}}
                    <td class="px-4 py-3">
                        <div class="flex space-x-3">
                            {{-- EDIT --}}
                            <a href="{{ route('owner.treatment.edit', $treatment->id_treatment) }}"
                               class="text-blue-600 hover:text-blue-800 transition-all duration-200" 
                               title="Edit Treatment"> 
                                <iconify-icon icon="mingcute:edit-line" class="text-xl"></iconify-icon>
                            </a>

                            {{-- DELETE --}}
                            <form action="{{ route('owner.treatment.destroy', $treatment->id_treatment) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Yakin ingin menghapus treatment {{ $treatment->nama_treatment }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 transition-all duration-200"
                                        title="Hapus Treatment">
                                    <iconify-icon icon="material-symbols:delete-outline" class="text-xl"></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center py-8">
                            <i class="fa-solid fa-inbox text-5xl text-gray-300 mb-3"></i>
                            <p class="text-lg font-medium">Belum ada data treatment</p>
                            <p class="text-sm text-gray-400 mt-1">Mulai dengan menambahkan treatment pertama</p>
                            <a href="{{ route('owner.treatment.add') }}" 
                               class="mt-4 inline-flex items-center px-4 py-2 bg-[#806B3F] text-white rounded-lg hover:bg-[#A18F5E] transition">
                                <i class="fa-solid fa-plus mr-2"></i>
                                Tambah Treatment
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $treatments->links() }}
        </div>
    </div>
</div>

{{-- Modal untuk preview gambar --}}
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white text-2xl hover:text-gray-300">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-[90vh] rounded-lg">
        <p id="modalCaption" class="text-white text-center mt-2"></p>
    </div>
</div>

<script>
function showImageModal(url, caption) {
    document.getElementById('modalImage').src = url;
    document.getElementById('modalCaption').textContent = caption;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endsection