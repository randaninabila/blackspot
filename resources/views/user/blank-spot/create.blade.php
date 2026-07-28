@extends('app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('user.dashboard') }}"
            class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#234B26] text-white hover:bg-[#1a381c] transition shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-[#234B26]">Tambah Data Blank Spot</h1>
        <span class="ml-auto text-sm text-gray-500 font-medium bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-200">
            {{ auth()->user()->kabupaten->nama_kabupaten ?? 'Kabupaten Anda' }}
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

        <form action="{{ route('user.blank-spot.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Kabupaten (readonly, otomatis dari user) -->
            <div>
                <label class="block text-[#234B26] font-bold text-sm mb-1.5">Kabupaten/Kota</label>
                <input type="text" value="{{ $kabupaten->nama_kabupaten ?? '-' }}" readonly
                    class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-600 cursor-not-allowed">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kecamatan -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Kecamatan <span class="text-red-500">*</span></label>
                    <select name="kecamatan_id" id="kecamatan_id" required
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all appearance-none">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                {{ $kec->nama_kecamatan }}
                            </option>
                        @endforeach
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

                <!-- Tahun -->
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-1.5">Tahun <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        value="{{ date('Y') }}"
                        readonly
                        class="w-full bg-[#F3F3E8] border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 cursor-not-allowed"
                    >
                    <input
                        type="hidden"
                        name="tahun"
                        value="{{ date('Y') }}">
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

            <!-- Status Jaringan (Free Textbox Input) -->
            <div>
                <label class="block text-[#234B26] font-bold text-sm mb-1.5">Status Jaringan</label>
                <input type="text" name="status_jaringan" value="{{ old('status_jaringan') }}"
                    placeholder="Contoh: Blank Spot Total, Sinyal Lemah, 4G Tidak Stabil..."
                    class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all">
            </div>

            <!-- Prioritas (P1-P10) -->
            <div>
                <label class="block text-[#234B26] font-bold text-sm mb-1.5">Tingkat Prioritas (P1–P10) <span class="text-red-500">*</span></label>
                <select name="prioritas" required
                    class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all appearance-none"
                    style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; padding-right: 36px;">
                    <option value="">-- Pilih Prioritas --</option>
                    @foreach(\App\Models\BlankSpot::PRIORITAS_LABELS as $level => $desc)
                        <option value="{{ $level }}" {{ old('prioritas') == $level ? 'selected' : '' }}>
                            Prioritas {{ $level }} (P{{ $level }}) - {{ $desc }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- FOTO BLANKSPOT SECTION -->
            <div class="space-y-4 pt-2">
                <h3 class="text-base font-bold text-[#234B26] border-b border-[#234B26]/20 pb-1">Upload Foto Dokumentasi</h3>
                
                <!-- Foto 1 -->
                <div>
                    <label class="block text-[#234B26] font-semibold text-sm mb-1">Foto Blankspot 1 <span class="text-red-500">*</span></label>
                    <input type="file" name="photos[]" accept="image/jpeg,image/jpg,image/png,image/webp" required
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl p-2 text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#234B26] file:text-white hover:file:bg-[#1a381c]">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG, WEBP. Maksimal 5 MB.</p>
                </div>

                <!-- Foto 2 -->
                <div>
                    <label class="block text-[#234B26] font-semibold text-sm mb-1">Foto Blankspot 2</label>
                    <input type="file" name="photos[]" accept="image/jpeg,image/jpg,image/png,image/webp"
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl p-2 text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#234B26] file:text-white hover:file:bg-[#1a381c]">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG, WEBP. Maksimal 5 MB.</p>
                </div>

                <div id="user-dynamic-photos-form-container" class="space-y-4"></div>

                <div>
                    <button type="button" onclick="addUserPagePhotoField()" id="btn-add-user-page-photo" class="bg-[#234B26] text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-[#1a381c] transition inline-flex items-center gap-1 shadow-sm">
                        + Tambah Foto
                    </button>
                    <span class="text-xs text-gray-500 ml-2">Maksimal 10 foto.</span>
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-[#234B26] font-bold text-sm mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="3"
                    placeholder="Deskripsi kondisi blank spot..."
                    class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all resize-none">{{ old('keterangan') }}</textarea>
            </div>

            <!-- PANDUAN STATUS JARINGAN -->
            <div class="bg-[#D7E3D4]/50 border border-[#234B26]/20 rounded-xl p-4 text-xs text-[#234B26]">
                <p class="font-bold mb-1.5">Panduan Pengisian Status Jaringan:</p>
                <ul class="list-disc list-inside space-y-1 text-gray-700">
                    @foreach(\App\Models\BlankSpot::STATUS_JARINGAN_GUIDE as $key => $guide)
                        <li><strong>{{ $key }}</strong>: {{ $guide }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Informasi -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-sm text-yellow-700">
                 Data yang dikirim akan berstatus <strong>Menunggu Validasi</strong> hingga disetujui oleh Admin Diskominfo
            </div>

            <!-- Tombol -->
            <div class="flex gap-4 pt-2">
                <button type="submit"
                    class="bg-[#234B26] text-white px-8 py-3 rounded-xl font-semibold hover:bg-[#1a381c] transition shadow-md">
                    Kirim Data
                </button>
                <a href="{{ route('user.dashboard') }}"
                    class="border border-[#234B26] text-[#234B26] px-8 py-3 rounded-xl font-semibold hover:bg-[#D7E3D4] transition">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

let userPagePhotoCount = 2;
function addUserPagePhotoField() {
    if (userPagePhotoCount >= 10) {
        alert('Maksimal 10 foto yang dapat diunggah.');
        return;
    }
    userPagePhotoCount++;
    const container = document.getElementById('user-dynamic-photos-form-container');
    if (!container) return;

    const div = document.createElement('div');
    div.id = `user-page-photo-group-${userPagePhotoCount}`;
    div.innerHTML = `
        <div class="flex justify-between items-center mb-1">
            <label class="block text-[#234B26] font-semibold text-sm">Foto Blankspot ${userPagePhotoCount}</label>
            <button type="button" onclick="document.getElementById('user-page-photo-group-${userPagePhotoCount}').remove(); userPagePhotoCount--; updateUserPagePhotoBtnState();" class="text-red-500 hover:text-red-700 text-xs font-semibold">Hapus</button>
        </div>
        <input type="file" name="photos[]" accept="image/jpeg,image/jpg,image/png,image/webp"
            class="w-full bg-white border border-[#234B26]/30 rounded-xl p-2 text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#234B26] file:text-white hover:file:bg-[#1a381c]">
        <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG, WEBP. Maksimal 5 MB.</p>
    `;
    container.appendChild(div);
    updateUserPagePhotoBtnState();
}

function updateUserPagePhotoBtnState() {
    const btn = document.getElementById('btn-add-user-page-photo');
    if (btn) {
        if (userPagePhotoCount >= 10) {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
}
</script>

<style>
    /* Style dropdown agar konsisten dengan input text */
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