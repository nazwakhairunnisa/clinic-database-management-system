@extends('layouts.admin')

@section('pageTitle', 'Edit Rekam Medis Pasien')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<div class="font-['Roboto'] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-8 sm:pt-12">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl p-6 sm:p-8 transition-all duration-300">
        
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-lg sm:text-xl font-semibold text-black">Edit Rekam Medis Pasien</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $pasien->nama_lengkap }}</p>
            </div>
            <a href="{{ route('admin.pasien.detail', $pasien->id_pasien) }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </a>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('admin.pasien.updateRekam', $pasien->id_pasien) }}" method="POST" class="space-y-5 text-sm text-gray-700">
            @csrf
            @method('PUT')

            {{-- Keluhan --}}
            <div>
                <label class="font-medium text-gray-700">Keluhan <span class="text-red-500">*</span></label>
                <textarea name="keluhan" rows="3" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none mt-2">{{ old('keluhan', $rekamMedis->keluhan ?? '') }}</textarea>
            </div>

            {{-- Jenis Kulit --}}
            <div>
                <label class="font-medium text-gray-700">Jenis Kulit <span class="text-red-500">*</span></label>
                <div class="flex flex-wrap gap-4 mt-2">
                    @foreach(['normal' => 'Normal', 'dry' => 'Dry', 'oily' => 'Oily', 'sensitive' => 'Sensitive', 'kombinasi' => 'Kombinasi'] as $value => $label)
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="jenis_kulit" value="{{ $value }}" 
                                {{ old('jenis_kulit', $rekamMedis->jenis_kulit ?? '') == $value ? 'checked' : '' }}
                                class="text-[#0073d9] focus:ring-[#EED892]" required>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Kelembapan --}}
            <div>
                <label class="font-medium text-gray-700">Kelembapan <span class="text-red-500">*</span></label>
                <div class="flex flex-wrap gap-4 mt-2">
                    @foreach(['baik' => 'Baik', 'cukup' => 'Cukup', 'kurang' => 'Kurang'] as $value => $label)
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="kelembapan" value="{{ $value }}" 
                                {{ old('kelembapan', $rekamMedis->kelembapan ?? '') == $value ? 'checked' : '' }}
                                class="text-[#0073d9] focus:ring-[#EED892]" required>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Kondisi Kulit (Acne, Hyperpigmentasi, Kerutan, Scar) --}}
            @php
            $kondisiKulitTypes = [
                'acne' => 'Acne',
                'hyperpigmentasi' => 'Hyperpigmentasi',
                'kerutan' => 'Kerutan',
                'scar' => 'Scar'
            ];
            
            // Get existing kondisi if available
            $existingKondisi = [];
            if($rekamMedis) {
                foreach($rekamMedis->kondisiKulit as $k) {
                    $existingKondisi[strtolower($k->jenis_kondisi)] = $k;
                }
            }
            @endphp

            <div id="kondisi-kulit-container">
                @foreach($kondisiKulitTypes as $key => $label)
                @php
                $existing = $existingKondisi[$key] ?? null;
                @endphp
                <div class="kondisi-item border border-gray-200 rounded-lg p-4 mb-3">
                    <label class="font-medium text-gray-700 block mb-2">{{ $label }}</label>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Hidden field for jenis_kondisi --}}
                        <input type="hidden" name="kondisi_kulit[{{ $loop->index }}][jenis_kondisi]" value="{{ $label }}">
                        
                        {{-- Status --}}
                        <div class="flex gap-2">
                            <label class="flex items-center space-x-1">
                                <input type="radio" 
                                    name="kondisi_kulit[{{ $loop->index }}][status_kondisi]" 
                                    value="ada" 
                                    {{ old("kondisi_kulit.{$loop->index}.status_kondisi", $existing->status_kondisi ?? '') == 'ada' ? 'checked' : '' }}
                                    class="text-[#0073d9] focus:ring-[#EED892] status-radio" 
                                    data-index="{{ $loop->index }}"
                                    required>
                                <span>Ada</span>
                            </label>
                            <label class="flex items-center space-x-1">
                                <input type="radio" 
                                    name="kondisi_kulit[{{ $loop->index }}][status_kondisi]" 
                                    value="tidak ada" 
                                    {{ old("kondisi_kulit.{$loop->index}.status_kondisi", $existing->status_kondisi ?? 'tidak ada') == 'tidak ada' ? 'checked' : '' }}
                                    class="text-[#0073d9] focus:ring-[#EED892] status-radio" 
                                    data-index="{{ $loop->index }}"
                                    required>
                                <span>Tidak Ada</span>
                            </label>
                        </div>

                        {{-- Area --}}
                        <input type="text" 
                            name="kondisi_kulit[{{ $loop->index }}][area]" 
                            value="{{ old("kondisi_kulit.{$loop->index}.area", $existing->area ?? '') }}"
                            placeholder="Area" 
                            class="area-input border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-[#EED892] outline-none"
                            {{ old("kondisi_kulit.{$loop->index}.status_kondisi", $existing->status_kondisi ?? 'tidak ada') == 'tidak ada' ? 'disabled' : '' }}
                            required>

                        {{-- Derajat --}}
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500">Derajat:</span>
                            @foreach(['ringan' => 'Ringan', 'sedang' => 'Sedang', 'berat' => 'Berat'] as $deg_value => $deg_label)
                            <label class="flex items-center space-x-1">
                                <input type="radio" 
                                    name="kondisi_kulit[{{ $loop->parent->index }}][derajat]" 
                                    value="{{ $deg_value }}" 
                                    {{ old("kondisi_kulit.{$loop->parent->index}.derajat", $existing->derajat ?? '') == $deg_value ? 'checked' : '' }}
                                    class="derajat-radio text-[#0073D9] focus:ring-[#EED892]"
                                    {{ old("kondisi_kulit.{$loop->parent->index}.status_kondisi", $existing->status_kondisi ?? 'tidak ada') == 'tidak ada' ? 'disabled' : '' }}>
                                <span class="text-xs">{{ $deg_label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Kondisi Pasien --}}
            <div>
                <label class="font-medium text-gray-700">Kondisi Pasien <span class="text-red-500">*</span></label>
                <div class="flex flex-wrap gap-4 mt-2">
                    @php
                    $kondisiPasienArray = old('kondisi_pasien', 
                        $rekamMedis ? explode(',', $rekamMedis->kondisi_pasien) : ['normal']
                    );
                    @endphp
                    @foreach(['hamil' => 'Hamil', 'menyusui' => 'Menyusui', 'kontrasepsi' => 'Kontrasepsi', 'normal' => 'Normal'] as $value => $label)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" 
                                name="kondisi_pasien[]" 
                                value="{{ $value }}" 
                                {{ in_array($value, $kondisiPasienArray) ? 'checked' : '' }}
                                class="kondisi-pasien-checkbox text-[#0073D9] focus:ring-[#EED892]">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Produk & Riwayat --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="font-medium text-gray-700">Produk/Peralatan terakhir dipakai</label>
                    <input type="text" name="produk_terakhir_dipakai" 
                        value="{{ old('produk_terakhir_dipakai', $rekamMedis->produk_terakhir_dipakai ?? '') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none mt-2">
                </div>
                <div>
                    <label class="font-medium text-gray-700">Riwayat Penyakit yang Diderita</label>
                    <input type="text" name="riwayat_penyakit" 
                        value="{{ old('riwayat_penyakit', $rekamMedis->riwayat_penyakit ?? '') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none mt-2">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="font-medium text-gray-700">Riwayat Pengobatan</label>
                    <input type="text" name="riwayat_pengobatan" 
                        value="{{ old('riwayat_pengobatan', $rekamMedis->riwwayat_pengobatan ?? '') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none mt-2">
                </div>
                <div>
                    <label class="font-medium text-gray-700">Riwayat Alergi</label>
                    <input type="text" name="riwayat_alergi" 
                        value="{{ old('riwayat_alergi', $rekamMedis->riwayat_alergi ?? '') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none mt-2">
                </div>
            </div>

            {{-- Save Button --}}
            <div class="pt-4 flex gap-3">
                <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white px-6 py-2 rounded-lg transition-all duration-300 flex items-center gap-2">
                    <iconify-icon icon="mdi:content-save" class="text-lg"></iconify-icon>
                    Save
                </button>
                <a href="{{ route('admin.pasien.detail', $pasien->id_pasien) }}" 
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-all duration-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Handle kondisi pasien checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.kondisi-pasien-checkbox');
    const normalCheckbox = document.querySelector('input[value="normal"]');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.value === 'normal' && this.checked) {
                // Jika normal dicentang, uncheck yang lain
                checkboxes.forEach(cb => {
                    if (cb.value !== 'normal') cb.checked = false;
                });
            } else if (this.value !== 'normal' && this.checked) {
                // Jika yang lain dicentang, uncheck normal
                if (normalCheckbox) normalCheckbox.checked = false;
            }
            
            // Minimal 1 harus tercentang
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            if (!anyChecked && normalCheckbox) {
                normalCheckbox.checked = true;
            }
        });
    });

    // Handle kondisi kulit status change
    const statusRadios = document.querySelectorAll('.status-radio');
    
    statusRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const index = this.dataset.index;
            const kondisiItem = this.closest('.kondisi-item');
            const areaInput = kondisiItem.querySelector('.area-input');
            const derajatRadios = kondisiItem.querySelectorAll('.derajat-radio');
            
            if (this.value === 'tidak ada') {
                // Disable area & derajat jika tidak ada
                areaInput.disabled = true;
                areaInput.value = '';
                derajatRadios.forEach(r => {
                    r.disabled = true;
                    r.checked = false;
                });
            } else {
                // Enable area & derajat jika ada
                areaInput.disabled = false;
                derajatRadios.forEach(r => r.disabled = false);
            }
        });
    });
});
</script>

@endsection