@extends('app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('user.blank-spot.index') }}"
            class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#234B26] text-white hover:bg-[#1a381c] transition shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-[#234B26]">Edit Data Blank Spot</h1>
        <span class="ml-auto text-sm text-gray-500 font-medium bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-200">
            ID: BS-{{ $blankSpot->tahun }}-{{ str_pad($blankSpot->id, 4, '0', STR_PAD_LEFT) }}
        </span>
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

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('user.blank-spot.update', $blankSpot->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kabupaten/Kota - READONLY -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Kabupaten/Kota</label>
                    <input type="text" value="{{ $kabupaten->nama_kabupaten ?? '-' }}" readonly
                        class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-600 cursor-not-allowed">
                </div>

                <!-- Kecamatan - DROPDOWN -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Kecamatan <span class="text-red-500">*</span></label>
                    <select name="kecamatan_id" id="kecamatan_id" required
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all appearance-none"
                        style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; padding-right: 36px;">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ $blankSpot->kecamatan_id == $kec->id ? 'selected' : '' }}>
                                {{ $kec->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Desa - INPUT TEXT (bukan dropdown) -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Nama Desa <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_desa" id="nama_desa"
                        value="{{ $blankSpot->desa->nama_desa ?? old('nama_desa') }}"
                        placeholder="Ketik nama desa..."
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all">
                </div>

                <!-- Tahun - DROPDOWN -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Tahun</label>
                    <select name="tahun" required
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all appearance-none"
                        style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; padding-right: 36px;">
                        <option value="">-- Pilih Tahun --</option>
                        <option value="2025" {{ $blankSpot->tahun == 2025 ? 'selected' : '' }}>2025</option>
                        <option value="2026" {{ $blankSpot->tahun == 2026 ? 'selected' : '' }}>2026</option>
                    </select>
                </div>

                <!-- Latitude -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Latitude <span class="text-red-500">*</span></label>
                    <input type="number" name="latitude" step="0.00000001"
                        value="{{ $blankSpot->latitude }}"
                        placeholder="Contoh: 3.591596"
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all">
                </div>

                <!-- Longitude -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Longitude <span class="text-red-500">*</span></label>
                    <input type="number" name="longitude" step="0.00000001"
                        value="{{ $blankSpot->longitude }}"
                        placeholder="Contoh: 98.672273"
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all">
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-[#234B26] font-bold text-sm mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="3"
                    placeholder="Deskripsi kondisi blank spot..."
                    class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all resize-none">{{ $blankSpot->keterangan }}</textarea>
            </div>

            <!-- Informasi -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-sm text-yellow-700">
                ⚠️ Setelah diedit, data akan kembali ke status <strong>Menunggu Validasi</strong> dan perlu disetujui ulang oleh Admin
            </div>

            <!-- Tombol -->
            <div class="flex gap-4 pt-2">
                <button type="submit"
                    class="bg-[#234B26] text-white px-8 py-3 rounded-xl font-semibold hover:bg-[#1a381c] transition shadow-md">
                    Simpan Perubahan
                </button>
                <a href="{{ route('user.blank-spot.index') }}"
                    class="border border-[#234B26] text-[#234B26] px-8 py-3 rounded-xl font-semibold hover:bg-[#D7E3D4] transition">
                    Batal
                </a>
            </div>

            {{-- Hidden input untuk desa_id (akan diisi oleh backend) --}}
            <input type="hidden" name="desa_id" value="{{ $blankSpot->desa_id }}">

        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kecSelect = document.getElementById('kecamatan_id');
    const namaDesaInput = document.getElementById('nama_desa');

    // Tidak perlu fetch desa karena user input manual
    // Kecamatan hanya untuk validasi di backend
    
    console.log('Form edit user siap');
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