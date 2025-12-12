@extends('layouts.admin')  

@section('pageTitle', 'Daftar Promo')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    /* Hilangkan ikon default dropdown */
    select {
        -webkit-appearance: none;
        appearance: none;
    }

    /* Hilangkan icon default date */
    input[type="date"]::-webkit-calendar-picker-indicator {
        opacity: 0 !important;
    }

    /* Hilangkan underline & border */
    select, select *,
    input[type="date"], input[type="date"] * {
        text-decoration: none !important;
        outline: none !important;
        border: none !important;
        box-shadow: none !important;
    }

    /* Focus outline hitam untuk date */
    input[type="date"]:focus {
        box-shadow: inset 0 0 0 2px #000 !important;
    }

    /* Chevron rotate */
    .rotate-180 {
        transform: rotate(180deg);
    }
    .chev-transition {
        transition: transform 0.25s ease;
    }

    /* Border table */
    .table-bordered tbody tr {
        border-bottom: 1px solid #000;
    }
</style>

<div class="max-w-full font-['Roboto',sans-serif]">

    {{-- FULL HEADER FILTER RESPONSIVE --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">

        {{-- LEFT GROUP — Search + Date --}}
        <div class="flex flex-wrap gap-2 sm:gap-3 items-center w-full sm:w-auto">

            {{-- SEARCH --}}
            <form method="GET" action="{{ route('admin.promo.index') }}" class="relative w-60">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search promo"
                    class="border border-gray-300 rounded-full pl-10 pr-4 py-2 w-full 
                           text-sm focus:outline-none focus:ring-2 focus:ring-[#EED892]">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
            </form>

            {{-- DATE PICKER --}}
            <form method="GET" action="{{ route('admin.promo.index') }}" id="dateFilterForm">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <div class="relative">
                    <iconify-icon 
                        icon="tabler:calendar"
                        class="text-[#4A3B1C] text-base absolute left-3 top-1/2 
                               -translate-y-1/2 pointer-events-none">
                    </iconify-icon>

                    <input type="date"
                        id="promoDate"
                        name="date"
                        value="{{ request('date') }}"
                        onchange="document.getElementById('dateFilterForm').submit()"
                        class="bg-[#EED892] text-[#4A3B1C] pl-10 pr-10 h-9
                               rounded-full text-sm cursor-pointer appearance-none">

                    <iconify-icon 
                        id="chevPromoDate"
                        icon="tabler:chevron-down"
                        class="text-[#4A3B1C] text-base absolute right-3 top-1/2 
                               -translate-y-1/2 cursor-pointer chev-transition">
                    </iconify-icon>
                </div>
            </form>

            {{-- CLEAR FILTER --}}
            @if(request('date') || request('search'))
                <a href="{{ route('admin.promo.index') }}"
                   class="text-sm text-gray-600 hover:text-gray-800 underline">
                    Clear Filter
                </a>
            @endif

            {{-- EXPORT (MOBILE ONLY) --}}
            <a href="{{ route('admin.promo.export') }}"
                class="flex sm:hidden items-center justify-center bg-[#806B3F] text-white px-3 py-2 rounded-full
                hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm">
                <iconify-icon icon="bx:export" class="text-xl"></iconify-icon>
            </a>

            {{-- ADD PROMO (MOBILE ONLY) --}}
            <a href="{{ route('admin.promo.add') }}"
                class="flex sm:hidden items-center justify-center bg-[#806B3F] text-white px-3 py-2 rounded-full
                hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm">
                <iconify-icon icon="material-symbols:add-rounded" class="text-xl"></iconify-icon>
            </a>

        </div>

        {{-- RIGHT GROUP - DESKTOP ONLY --}}
        <div class="hidden sm:flex items-center gap-3">

            {{-- Export --}}
            <a href="{{ route('admin.promo.export') }}"
                class="flex items-center justify-center bg-[#806B3F] text-white px-4 py-2 rounded-full
                hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm">
                <iconify-icon icon="bx:export" class="text-xl mr-2"></iconify-icon>
                Export
            </a>

            {{-- Add Promo --}}
            <a href="{{ route('admin.promo.add') }}"
                class="flex items-center justify-center bg-[#806B3F] text-white px-4 py-2 rounded-full
                hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm">
                <iconify-icon icon="material-symbols:add-rounded" class="text-xl mr-2"></iconify-icon>
                Add Promo
            </a>

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

    {{-- TABLE PROMO --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto">
        
        <table class="min-w-[900px] w-full text-sm text-left border-collapse table-bordered">
            
            <thead>
                <tr class="bg-gray-100/60 text-gray-700">
                    <th class="px-4 py-3 rounded-l-lg border-b border-gray-300 font-light">No</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">ID Promo</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Treatment</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Nama Promo</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Harga Normal</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Harga Promo</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Hemat</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Periode</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Status</th>
                    <th class="px-4 py-3 border-b border-gray-300 font-light">Gambar</th>
                    <th class="px-4 py-3 rounded-r-lg border-b border-gray-300 font-light">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">

                @forelse($promos as $index => $promo)
                <tr class="bg-white hover:bg-gray-50 transition border-b border-gray-200">
                    <td class="px-4 py-3">{{ $promos->firstItem() + $index }}</td>
                    
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">
                            P{{ str_pad($promo->promo_id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    
                    <td class="px-4 py-3 font-medium">
                        {{ $promo->treatment->nama_treatment ?? '-' }}
                    </td>
                    
                    <td class="px-4 py-3">{{ $promo->nama_promo }}</td>
                    
                    <td class="px-4 py-3">
                        <span class="text-gray-600 font-medium">
                            {{ $promo->treatment ? $promo->treatment->formatted_harga : '-' }}
                        </span>
                    </td>
                    
                    <td class="px-4 py-3">
                        <span class="text-green-600 font-semibold">
                            {{ $promo->formatted_harga_promo }}
                        </span>
                    </td>
                    
                    <td class="px-4 py-3">
                        @if($promo->treatment)
                            <span class="text-orange-600 text-sm">
                                <i class="fa-solid fa-tag"></i>
                                {{ number_format($promo->hemat, 0, ',', '.') }}
                                ({{ $promo->persen_diskon }}%)
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    
                    <td class="px-4 py-3">
                        <div class="text-xs">
                            <div>{{ $promo->periode_mulai->format('d M Y') }}</div>
                            <div class="text-gray-500">s/d</div>
                            <div>{{ $promo->periode_selesai->format('d M Y') }}</div>
                        </div>
                    </td>
                    
                    <td class="px-4 py-3">
                        @if($promo->isActive())
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fa-solid fa-circle-check mr-1"></i> Aktif
                            </span>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $promo->sisa_hari }} hari lagi
                            </p>
                        @elseif($promo->periode_mulai > now())
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fa-regular fa-clock mr-1"></i> Akan Datang
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fa-solid fa-ban mr-1"></i> Berakhir
                            </span>
                        @endif
                    </td>
                    
                    <td class="px-4 py-3">
                        @if($promo->gambar_promo)
                            <img src="{{ $promo->gambar_url }}" 
                                 alt="{{ $promo->nama_promo }}" 
                                 class="w-16 h-16 object-cover rounded-lg shadow-sm hover:scale-110 transition-transform duration-200 cursor-pointer"
                                 onclick="showImageModal('{{ $promo->gambar_url }}', '{{ $promo->nama_promo }}')">
                        @else
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-image text-gray-400 text-xl"></i>
                            </div>
                        @endif
                    </td>

                    {{-- ACTION --}}
                    <td class="px-4 py-3">
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.promo.edit', $promo->promo_id) }}"
                                class="text-blue-600 hover:text-blue-800 transition-all"
                                title="Edit Promo">
                                <iconify-icon icon="mingcute:edit-line" class="text-xl"></iconify-icon>
                            </a>

                            <form action="{{ route('admin.promo.destroy', $promo->promo_id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Yakin ingin menghapus promo {{ $promo->nama_promo }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 transition-all duration-200"
                                        title="Hapus Promo">
                                    <iconify-icon icon="material-symbols:delete-outline" class="text-xl"></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="11" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center py-8">
                            <i class="fa-solid fa-tags text-5xl text-gray-300 mb-3"></i>
                            <p class="text-lg font-medium">Belum ada data promo</p>
                            <p class="text-sm text-gray-400 mt-1">Mulai dengan menambahkan promo pertama</p>
                            <a href="{{ route('admin.promo.add') }}" 
                               class="mt-4 inline-flex items-center px-4 py-2 bg-[#806B3F] text-white rounded-lg hover:bg-[#A18F5E] transition">
                                <i class="fa-solid fa-plus mr-2"></i>
                                Tambah Promo
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $promos->links() }}
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

{{-- SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const promoDate = document.getElementById("promoDate");
    const chevPromoDate = document.getElementById("chevPromoDate");

    // Chevron click → open picker
    chevPromoDate.addEventListener("click", () => {
        promoDate.showPicker();
        chevPromoDate.classList.add("rotate-180");
    });

    // Input click → rotate
    promoDate.addEventListener("click", () => {
        promoDate.showPicker();
        chevPromoDate.classList.add("rotate-180");
    });

    // Blur → rotate back
    promoDate.addEventListener("blur", () => {
        chevPromoDate.classList.remove("rotate-180");
    });
});

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