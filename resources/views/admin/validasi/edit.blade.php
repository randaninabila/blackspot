@extends('app')

@section('content')

<section class="max-w-7xl mx-auto py-10 px-8 relative">

    <div class="flex items-center gap-5 mb-6">

        <!-- BACK BUTTON -->
        <button onclick="history.back()"
            class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#234B26] text-white hover:bg-[#1a381c] transition shadow-md">

            <svg xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="2.5"
                stroke="currentColor"
                class="w-5 h-5">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <!-- TITLE -->
        <h2 class="text-3xl font-bold text-[#234B26]">
            Edit Data Validasi
        </h2>

        <span class="ml-auto text-sm text-gray-500 font-medium bg-white px-4 py-2 rounded-xl shadow-sm">
            ID: BS-{{ $blankSpot->tahun }}-{{ str_pad($blankSpot->id, 4, '0', STR_PAD_LEFT) }}
        </span>

    </div>

    <div class="bg-[#F3F3E8] rounded-3xl shadow-2xl p-8">

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.validasi.update', $blankSpot->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Kabupaten/Kota -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Kabupaten/Kota</label>
                    <select name="kabupaten_id" id="kabupaten_id"
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all appearance-none">
                        <option value="">Pilih Kabupaten/Kota</option>
                        @foreach($kabupatens as $kab)
                            <option value="{{ $kab->id }}" {{ $blankSpot->kabupaten_id == $kab->id ? 'selected' : '' }}>
                                {{ $kab->nama_kabupaten }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kecamatan -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Kecamatan</label>
                    <select name="kecamatan_id" id="kecamatan_id"
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all appearance-none">
                        <option value="">Pilih Kecamatan</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ $blankSpot->kecamatan_id == $kec->id ? 'selected' : '' }}>
                                {{ $kec->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Desa -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Desa</label>
                    <select name="desa_id" id="desa_id"
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all appearance-none">
                        <option value="">Pilih Desa</option>
                        @foreach($desas as $desa)
                            <option value="{{ $desa->id }}" {{ $blankSpot->desa_id == $desa->id ? 'selected' : '' }}>
                                {{ $desa->nama_desa }}
                            </option>
                        @endforeach
                    </select>
                </div>

               <!-- Tahun -->
<div>
    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Tahun</label>
    <select name="tahun"
        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all appearance-none">
        <option value="">-- Pilih Tahun --</option>
        <option value="2025" {{ $blankSpot->tahun == 2025 ? 'selected' : '' }}>2025</option>
        <option value="2026" {{ $blankSpot->tahun == 2026 ? 'selected' : '' }}>2026</option>
    </select>
</div>

                <!-- Longitude -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Longitude</label>
                    <input type="number" name="longitude" step="0.00000001"
                        value="{{ $blankSpot->longitude }}"
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all">
                </div>

                <!-- Latitude -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Latitude</label>
                    <input type="number" name="latitude" step="0.00000001"
                        value="{{ $blankSpot->latitude }}"
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all">
                </div>

            </div>

            <!-- Status Validasi -->
            <div>
                <label class="block text-[#234B26] font-bold text-sm mb-1.5">Status Validasi</label>
                <select name="status_validasi"
                    class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all appearance-none">
                    <option value="pending" {{ $blankSpot->status_validasi == 'pending' ? 'selected' : '' }}>Menunggu Validasi</option>
                    <option value="approved" {{ $blankSpot->status_validasi == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ $blankSpot->status_validasi == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-[#234B26] font-bold text-sm mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="3"
                    class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all resize-none">{{ $blankSpot->keterangan }}</textarea>
            </div>

            <!-- BUTTONS -->
            <div class="flex gap-4 pt-4">
                <button type="submit"
                    class="bg-[#234B26] text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-[#1a381c] transition shadow-md">
                    Simpan Perubahan
                </button>

                <a href="{{ route('admin.validasi.index') }}"
                    class="border border-[#234B26] text-[#234B26] px-6 py-2.5 rounded-xl font-semibold hover:bg-[#D7E3D4] transition">
                    Batal
                </a>
            </div>

        </form>

    </div>

</section>

@push('scripts')
<script>
    // ========================================
    // CASCADING DROPDOWN: Kabupaten → Kecamatan → Desa
    // ========================================

    document.addEventListener('DOMContentLoaded', function() {
        const kabupatenSelect = document.getElementById('kabupaten_id');
        const kecamatanSelect = document.getElementById('kecamatan_id');
        const desaSelect = document.getElementById('desa_id');

        // Simpan value awal
        const initialKabupaten = kabupatenSelect.value;
        const initialKecamatan = kecamatanSelect.value;

        // Event: Ketika Kabupaten berubah
        kabupatenSelect.addEventListener('change', function() {
            const kabupatenId = this.value;

            // Reset dropdown kecamatan & desa
            kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

            if (!kabupatenId) return;

            // Fetch kecamatan dari API
            fetch('/admin/api/kecamatan/' + kabupatenId)
                .then(response => response.json())
                .then(data => {
                    kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                    data.forEach(function(kec) {
                        const option = document.createElement('option');
                        option.value = kec.id;
                        option.textContent = kec.nama_kecamatan;
                        kecamatanSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading kecamatan:', error);
                });
        });

        // Event: Ketika Kecamatan berubah
        kecamatanSelect.addEventListener('change', function() {
            const kecamatanId = this.value;

            // Reset dropdown desa
            desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

            if (!kecamatanId) return;

            // Fetch desa dari API
            fetch('/admin/api/desa/' + kecamatanId)
                .then(response => response.json())
                .then(data => {
                    desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
                    data.forEach(function(desa) {
                        const option = document.createElement('option');
                        option.value = desa.id;
                        option.textContent = desa.nama_desa;
                        desaSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading desa:', error);
                });
        });

        // Trigger change untuk load desa awal jika ada kecamatan terpilih
        if (initialKecamatan) {
            kecamatanSelect.dispatchEvent(new Event('change'));
        }
    });
</script>

<style>
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