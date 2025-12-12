@extends('layouts.admin')

@section('pageTitle', 'Jadwal Operasional Klinik')

@section('content')

{{-- Iconify --}}
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<style>
/* Reset default select styling */
select {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    background-image: none !important;
}

select::-ms-expand {
    display: none !important;
}

input[type="date"]::-webkit-calendar-picker-indicator {
    display: none !important;
}

input[type="time"]::-webkit-calendar-picker-indicator {
    display: none !important;
}

.chev-transition {
    transition: transform 0.2s;
}

.rotate-180 {
    transform: rotate(180deg);
}

/* Modal styling */
.modal {
    display: none;
    position: fixed;
    z-index: 999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
}

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: #fefefe;
    border-radius: 12px;
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}
</style>

<div class="max-w-full font-['Roboto',sans-serif]">

    {{-- SUCCESS/ERROR MESSAGE --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6 gap-3">
        
        {{-- Filter Bulan --}}
        <form method="GET" action="{{ route('admin.jadwal_operasional.index') }}" class="flex items-center gap-2">
            <label class="text-gray-700 font-medium">Bulan:</label>
            <div class="relative">
                <input type="month" 
                    name="bulan" 
                    value="{{ $bulan }}"
                    class="bg-[#EED892] text-[#4A3B1C] pl-4 pr-10 h-9 rounded-full text-sm cursor-pointer"
                    onchange="this.form.submit()">
                <iconify-icon 
                    icon="tabler:calendar"
                    class="text-[#4A3B1C] text-base absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                </iconify-icon>
            </div>
        </form>

        {{-- Action Buttons --}}
        <div class="flex gap-2">
            <button onclick="openBulkModal()" 
                class="flex items-center bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 text-sm shadow-sm">
                <iconify-icon icon="mdi:calendar-multiple" class="text-xl mr-1"></iconify-icon>
                Bulk Insert
            </button>

            <button onclick="openAddModal()" 
                class="flex items-center bg-[#806B3F] text-white px-4 py-2 rounded-full hover:bg-[#A18F5E] text-sm shadow-sm">
                <iconify-icon icon="mdi:plus-circle" class="text-xl mr-1"></iconify-icon>
                Tambah Jadwal
            </button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto">
        @if($jadwal->count() > 0)
        <table class="min-w-full w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-100/60 text-gray-700">
                    <th class="px-4 py-3 text-left rounded-l-lg border-b border-gray-300 font-light">No</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Tanggal</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Hari</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Jam Operasional</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Status</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Keterangan</th>
                    <th class="px-4 py-3 text-left rounded-r-lg border-b border-gray-300 font-light">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @foreach($jadwal as $index => $item)
                <tr class="bg-white hover:bg-gray-50 border-b border-gray-200">
                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item->hari_tanggal)->format('d M Y') }}</td>
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item->hari_tanggal)->locale('id')->isoFormat('dddd') }}</td>
                    <td class="px-4 py-3">
                        <span class="font-medium">{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}</span>
                        <span class="text-gray-500">-</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if($item->status_operasional == 'buka')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Buka
                        </span>
                        @else
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Tutup
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs text-gray-600">{{ $item->keterangan ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <button onclick="openEditModal({{ json_encode($item) }})" 
                                class="text-blue-600 hover:text-blue-800"
                                title="Edit">
                                <iconify-icon icon="mdi:pencil" class="text-xl"></iconify-icon>
                            </button>
                            
                            <button onclick="deleteJadwal({{ $item->id_jadwal }})" 
                                class="text-red-600 hover:text-red-800"
                                title="Hapus">
                                <iconify-icon icon="mdi:delete" class="text-xl"></iconify-icon>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @else
        <div class="text-center py-8 text-gray-500">
            <iconify-icon icon="mdi:calendar-blank" class="text-6xl mb-2"></iconify-icon>
            <p>Belum ada jadwal operasional untuk bulan ini</p>
            <button onclick="openAddModal()" 
                class="mt-4 inline-flex items-center bg-[#806B3F] text-white px-4 py-2 rounded-full hover:bg-[#A18F5E] text-sm">
                <iconify-icon icon="mdi:plus-circle" class="text-xl mr-1"></iconify-icon>
                Tambah Jadwal
            </button>
        </div>
        @endif
    </div>
</div>

{{-- MODAL ADD/EDIT --}}
<div id="jadwalModal" class="modal">
    <div class="modal-content">
        <div class="bg-[#806B3F] text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
            <h3 id="modalTitle" class="text-lg font-semibold">Tambah Jadwal Operasional</h3>
            <button onclick="closeModal()" class="text-white hover:text-gray-200">
                <iconify-icon icon="mdi:close" class="text-2xl"></iconify-icon>
            </button>
        </div>

        <form id="jadwalForm" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="space-y-4">
                {{-- Tanggal --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                        name="hari_tanggal" 
                        id="hari_tanggal"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#806B3F] focus:border-transparent">
                </div>

                {{-- Jam Mulai --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Jam Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                        name="jam_mulai" 
                        id="jam_mulai"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#806B3F] focus:border-transparent">
                </div>

                {{-- Jam Selesai --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Jam Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                        name="jam_selesai" 
                        id="jam_selesai"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#806B3F] focus:border-transparent">
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status_operasional" 
                        id="status_operasional"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#806B3F] focus:border-transparent">
                        <option value="buka">Buka</option>
                        <option value="tutup">Tutup</option>
                    </select>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Keterangan (Opsional)
                    </label>
                    <textarea name="keterangan" 
                        id="keterangan"
                        rows="3"
                        placeholder="Contoh: Dokter ada seminar pagi"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#806B3F] focus:border-transparent"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" 
                    onclick="closeModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">
                    Batal
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-[#806B3F] text-white rounded-lg hover:bg-[#A18F5E] text-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL BULK INSERT --}}
<div id="bulkModal" class="modal">
    <div class="modal-content">
        <div class="bg-blue-600 text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
            <h3 class="text-lg font-semibold">Bulk Insert Jadwal</h3>
            <button onclick="closeBulkModal()" class="text-white hover:text-gray-200">
                <iconify-icon icon="mdi:close" class="text-2xl"></iconify-icon>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.jadwal_operasional.bulk-store') }}" class="p-6">
            @csrf

            <div class="space-y-4">
                {{-- Range Tanggal --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                            name="tanggal_mulai" 
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                            name="tanggal_selesai" 
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    </div>
                </div>

                {{-- Hari Kerja --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Hari Kerja <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-4 gap-2">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="hari_kerja[]" value="1" class="rounded">
                            <span class="text-sm">Senin</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="hari_kerja[]" value="2" class="rounded">
                            <span class="text-sm">Selasa</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="hari_kerja[]" value="3" class="rounded">
                            <span class="text-sm">Rabu</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="hari_kerja[]" value="4" class="rounded">
                            <span class="text-sm">Kamis</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="hari_kerja[]" value="5" class="rounded">
                            <span class="text-sm">Jumat</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="hari_kerja[]" value="6" class="rounded">
                            <span class="text-sm">Sabtu</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="hari_kerja[]" value="0" class="rounded">
                            <span class="text-sm">Minggu</span>
                        </label>
                    </div>
                </div>

                {{-- Jam Operasional --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jam Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                            name="jam_mulai" 
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jam Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                            name="jam_selesai" 
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status_operasional" 
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        <option value="buka">Buka</option>
                        <option value="tutup">Tutup</option>
                    </select>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Keterangan (Opsional)
                    </label>
                    <textarea name="keterangan" 
                        rows="2"
                        placeholder="Contoh: Jadwal normal operasional"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:border-transparent"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" 
                    onclick="closeBulkModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">
                    Batal
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                    Generate Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal functions
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Jadwal Operasional';
    document.getElementById('jadwalForm').action = '{{ route("admin.jadwal_operasional.store") }}';
    document.getElementById('formMethod').value = 'POST';
    
    // Reset form
    document.getElementById('jadwalForm').reset();
    document.getElementById('hari_tanggal').disabled = false;
    
    // Set min date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('hari_tanggal').min = today;
    
    document.getElementById('jadwalModal').classList.add('show');
}

function openEditModal(jadwal) {
    document.getElementById('modalTitle').textContent = 'Edit Jadwal Operasional';
    document.getElementById('jadwalForm').action = `/admin/jadwal-operasional/${jadwal.id_jadwal}`;
    document.getElementById('formMethod').value = 'PUT';
    
    // Fill form
    document.getElementById('hari_tanggal').value = jadwal.hari_tanggal;
    document.getElementById('hari_tanggal').disabled = true; // Tanggal tidak bisa diubah
    document.getElementById('jam_mulai').value = jadwal.jam_mulai;
    document.getElementById('jam_selesai').value = jadwal.jam_selesai;
    document.getElementById('status_operasional').value = jadwal.status_operasional;
    document.getElementById('keterangan').value = jadwal.keterangan || '';
    
    document.getElementById('jadwalModal').classList.add('show');
}

function closeModal() {
    document.getElementById('jadwalModal').classList.remove('show');
}

function openBulkModal() {
    document.getElementById('bulkModal').classList.add('show');
}

function closeBulkModal() {
    document.getElementById('bulkModal').classList.remove('show');
}

// Delete function
function deleteJadwal(id) {
    if (!confirm('Yakin ingin menghapus jadwal ini? Pastikan tidak ada reservasi pada tanggal ini.')) {
        return;
    }

    fetch(`/admin/jadwal-operasional/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(res => {
        alert(res.message);
        location.reload();
    })
    .catch(err => {
        alert('Gagal menghapus: ' + err.message);
    });
}

// Close modal when clicking outside
window.onclick = function(event) {
    const jadwalModal = document.getElementById('jadwalModal');
    const bulkModal = document.getElementById('bulkModal');
    
    if (event.target === jadwalModal) {
        closeModal();
    }
    if (event.target === bulkModal) {
        closeBulkModal();
    }
}
</script>

@endsection