@extends('app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.add') }}"
            class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#234B26] text-white hover:bg-[#1a381c] transition shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-[#234B26]">Tambah Data Blank Spot</h1>
    </div>

    <div class="bg-[#F3F3E8] rounded-3xl shadow-xl p-8">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.blank-spot.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kabupaten/Kota -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Kabupaten/Kota <span class="text-red-500">*</span></label>
                    <select name="kabupaten_id" id="kabupaten_id" required
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all appearance-none"
                        style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; padding-right: 36px;">
                        <option value="">-- Pilih Kabupaten/Kota --</option>
                        @foreach($kabupatens as $kab)
                            <option value="{{ $kab->id }}" {{ old('kabupaten_id') == $kab->id ? 'selected' : '' }}>
                                {{ $kab->nama_kabupaten }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kecamatan -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Kecamatan <span class="text-red-500">*</span></label>
                    <select name="kecamatan_id" id="kecamatan_id" required
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all appearance-none"
                        style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; padding-right: 36px;">
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>

                <!-- Nama Desa -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Nama Desa <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_desa" id="nama_desa"
                        value="{{ old('nama_desa') }}"
                        placeholder="Ketik nama desa..."
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all">
                </div>

               <div>
    <label class="block text-[#234B26] font-bold text-sm mb-2">Tahun <span class="text-red-500">*</span></label>
    <select name="tahun" required
        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-3 text-sm outline-none focus:border-[#234B26]">
        <option value="">-- Pilih Tahun --</option>
        <option value="2025">2025</option>
        <option value="2026">2026</option>
    </select>
</div>

                <!-- Latitude -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Latitude <span class="text-red-500">*</span></label>
                    <input type="number" name="latitude" step="0.00000001"
                        value="{{ old('latitude') }}"
                        placeholder="Contoh: 3.591596"
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all">
                </div>

                <!-- Longitude -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Longitude <span class="text-red-500">*</span></label>
                    <input type="number" name="longitude" step="0.00000001"
                        value="{{ old('longitude') }}"
                        placeholder="Contoh: 98.672273"
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all">
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-[#234B26] font-bold text-sm mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="3"
                    placeholder="Deskripsi kondisi blank spot..."
                    class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all resize-none">{{ old('keterangan') }}</textarea>
            </div>

            <!-- Tombol -->
            <div class="flex gap-4 pt-2">
                <button type="submit"
                    class="bg-[#234B26] text-white px-8 py-3 rounded-xl font-semibold hover:bg-[#1a381c] transition shadow-md">
                    Simpan Data
                </button>
                <a href="{{ route('admin.add') }}"
                    class="border border-[#234B26] text-[#234B26] px-8 py-3 rounded-xl font-semibold hover:bg-[#D7E3D4] transition">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kabSelect = document.getElementById('kabupaten_id');
    const kecSelect = document.getElementById('kecamatan_id');

    kabSelect.addEventListener('change', function() {
        const kabId = this.value;

        if (!kabId) {
            kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            return;
        }

        kecSelect.innerHTML = '<option value="">-- Memuat... --</option>';

        fetch(`/admin/api/kecamatan/${kabId}`)
            .then(response => response.json())
            .then(data => {
                kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                data.forEach(kec => {
                    kecSelect.innerHTML += `<option value="${kec.id}">${kec.nama_kecamatan}</option>`;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                kecSelect.innerHTML = '<option value="">-- Gagal memuat data --</option>';
            });
    });

    // Trigger jika ada old value
    if (kabSelect.value) {
        kabSelect.dispatchEvent(new Event('change'));
    }
});
</script>

<style>
    /* Style dropdown agar konsisten dengan input */
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 12px;
        padding-right: 36px !important;
        cursor: pointer;
    }

    select:focus {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23234B26' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        border-color: #234B26 !important;
        outline: none;
    }

    select::-ms-expand {
        display: none;
    }
</style>
@endpush

@endsection