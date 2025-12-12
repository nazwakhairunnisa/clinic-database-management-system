@extends('layouts.admin')

@section('pageTitle', 'Tambah Pasien & Reservasi (Walk-in)')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<!-- TomboSelect (dropdown canggih) -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<div class="font-['Roboto'] bg-[#F8F6F1] min-h-screen py-8">
    <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-xl p-8">

        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Tambah Pasien Baru & Reservasi (Datang Langsung)</h2>
            <a href="{{ route('admin.pasien.index') }}" class="text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </a>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.pasien.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- 1. DATA PASIEN -->
            <div class="bg-gray-50 rounded-xl p-6 border">
                <h3 class="text-xl font-semibold mb-6 text-gray-800 flex items-center">
                    Informasi Pasien
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Depan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_depan" value="{{ old('nama_depan') }}" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Belakang <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_belakang" value="{{ old('nama_belakang') }}" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon <span class="text-red-500">*</span></label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="08123456789" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="alamat" rows="3" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 2. RESERVASI -->
            <div class="bg-gray-50 rounded-xl p-6 border">
                <h3 class="text-xl font-semibold mb-6 text-gray-800 flex items-center">
                    Jadwal Reservasi
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Reservasi <span class="text-red-500">*</span></label>
                        <input type="date" id="tanggal_reservasi" name="tanggal_reservasi"
                               value="{{ old('tanggal_reservasi', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        <p id="jadwalInfo" class="text-sm mt-2 min-h-6"></p>
                    </div>

                    <!-- Dropdown Treatment (TomboSelect) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Treatment <span class="text-red-500">*</span></label>
                        <select name="selected_treatments[]" id="treatment-select" multiple required
                                class="w-full border border-gray-300 rounded-lg"
                                placeholder="Ketik untuk mencari treatment...">
                            @foreach($treatments as $t)
                                <option value="{{ $t->id_treatment }}"
                                        data-harga="{{ $t->harga }}"
                                        data-durasi="{{ $t->durasi }}">
                                    {{ $t->nama_treatment }} - Rp {{ number_format($t->harga, 0, ',', '.') }} ({{ $t->durasi }} menit)
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-2">Bisa pilih lebih dari satu treatment</p>
                    </div>
                </div>

                <!-- Jam Tersedia -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Jam Tersedia <span class="text-red-500">*</span></label>
                    <div id="timeSlotContainer" class="bg-white border rounded-lg p-8 text-center text-gray-500 min-h-32">
                        Pilih tanggal dan treatment terlebih dahulu
                    </div>
                    <input type="hidden" name="jam_reservasi" id="jam_reservasi" required>
                </div>

                <!-- Total Biaya -->
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                    <div class="flex justify-between items-center text-2xl font-bold">
                        <span>TOTAL BIAYA</span>
                        <span id="totalBiaya" class="text-blue-700">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-4 pt-6">
                <button type="submit" class="bg-[#2F9CCA] hover:bg-[#1d7aa0] text-white font-bold py-4 px-10 rounded-lg transition shadow-lg flex items-center gap-2">
                    Simpan & Buat Reservasi
                </button>
                <a href="{{ route('admin.pasien.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 px-10 rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Inisialisasi TomboSelect
new TomSelect("#treatment-select", {
    maxItems: null,
    plugins: ['remove_button'],
    placeholder: "Pilih treatment (bisa lebih dari satu)...",
    render: {
        option: function(data, escape) {
            const parts = escape(data.text).split(' - ');
            return `<div class="py-2 px-3">
                        <div class="font-medium">${parts[0]}</div>
                        <div class="text-sm text-gray-600">${parts[1] || ''}</div>
                    </div>`;
        },
        item: function(data, escape) {
            return `<div class="py-1 px-3 bg-blue-100 text-blue-800 rounded-full text-sm">${escape(data.text.split(' - ')[0])}</div>`;
        }
    }
});

let maxDurasi = 0;

// Update total & durasi saat treatment berubah
function updateTreatments() {
    const select = document.getElementById('treatment-select');
    const tomSelect = select.tomselect;
    const items = tomSelect ? tomSelect.items : [];

    let total = 0;
    maxDurasi = 0;

    items.forEach(id => {
        const option = select.querySelector(`option[value="${id}"]`);
        if (option) {
            const harga = parseInt(option.dataset.harga);
            const durasi = parseInt(option.dataset.durasi);
            total += harga;
            if (durasi > maxDurasi) maxDurasi = durasi;
        }
    });

    document.getElementById('totalBiaya').textContent = 'Rp ' + total.toLocaleString('id-ID');
    loadTimeSlots();
}

// Cek jadwal operasional
function cekJadwal(tanggal) {
    if (!tanggal) return;
    fetch(`{{ route('admin.reservasi.get-jadwal') }}?tanggal=${tanggal}`)
        .then(r => r.json())
        .then(data => {
            const info = document.getElementById('jadwalInfo');
            if (data.success) {
                info.innerHTML = `Jam operasional: <strong>${data.jadwal.jam_mulai} - ${data.jadwal.jam_selesai}</strong>`;
                info.className = 'text-sm text-green-600 mt-2';
            } else {
                info.innerHTML = data.message || 'Klinik tutup';
                info.className = 'text-sm text-red-600 mt-2';
            }
            loadTimeSlots();
        });
}

// Load slot waktu
function loadTimeSlots() {
    const tanggal = document.getElementById('tanggal_reservasi').value;
    if (!tanggal || maxDurasi === 0) {
        document.getElementById('timeSlotContainer').innerHTML = '<p class="text-center text-gray-500">Pilih tanggal & treatment</p>';
        return;
    }

    document.getElementById('timeSlotContainer').innerHTML = '<p class="text-center text-gray-500">Memuat slot...</p>';

    fetch(`{{ route('admin.reservasi.get-time-slots') }}?tanggal=${tanggal}&durasi=${maxDurasi}`)
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('timeSlotContainer');
            container.innerHTML = '';

            if (!data.slots || data.slots.length === 0) {
                container.innerHTML = '<p class="text-center text-red-600 font-medium">Tidak ada slot tersedia</p>';
                return;
            }

            const grid = document.createElement('div');
            grid.className = 'grid grid-cols-4 md:grid-cols-6 gap-4';

            data.slots.forEach(slot => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = slot.jam;
                btn.className = 'px-6 py-4 border-2 rounded-lg font-medium hover:bg-blue-600 hover:text-white hover:border-blue-600 transition';
                btn.onclick = () => {
                    document.querySelectorAll('#timeSlotContainer button').forEach(b => {
                        b.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                        b.classList.add('border-gray-300');
                    });
                    btn.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                    document.getElementById('jam_reservasi').value = slot.jam;
                };
                grid.appendChild(btn);
            });
            container.appendChild(grid);
        })
        .catch(err => {
            console.error(err);
            document.getElementById('timeSlotContainer').innerHTML = '<p class="text-center text-red-600">Gagal memuat slot</p>';
        });
}

// Event Listeners
document.getElementById('tanggal_reservasi').addEventListener('change', function() {
    cekJadwal(this.value);
});
document.getElementById('treatment-select').addEventListener('change', updateTreatments);

// Init
updateTreatments();
</script>
@endsection