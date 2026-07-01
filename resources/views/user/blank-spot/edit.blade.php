@extends('app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    <div class="flex items-center gap-4 mb-8">
        <button onclick="history.back()"
            class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#234B26] text-white hover:bg-[#1a381c]">←</button>
        <h1 class="text-3xl font-bold text-[#234B26]">Edit Data Blank Spot</h1>
    </div>

    <div class="bg-[#F3F3E8] rounded-3xl shadow-xl p-8">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.blank-spot.update', $blankSpot->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[#234B26] font-bold text-sm mb-2">Kabupaten/Kota</label>
                <input type="text" value="{{ $kabupaten->nama_kabupaten ?? '-' }}" readonly
                    class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-600 cursor-not-allowed">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-2">Kecamatan <span class="text-red-500">*</span></label>
                    <select name="kecamatan_id" id="kecamatan_id" required
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-3 text-sm outline-none focus:border-[#234B26]">
                        <option value="">-- Pilih --</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ $blankSpot->kecamatan_id == $kec->id ? 'selected' : '' }}>
                                {{ $kec->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-2">Desa <span class="text-red-500">*</span></label>
                    <select name="desa_id" id="desa_id" required
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-3 text-sm outline-none focus:border-[#234B26]">
                        <option value="">-- Pilih --</option>
                        @foreach($desas as $d)
                            <option value="{{ $d->id }}" {{ $blankSpot->desa_id == $d->id ? 'selected' : '' }}>
                                {{ $d->nama_desa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-2">Tahun <span class="text-red-500">*</span></label>
                    <select name="tahun" required
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-3 text-sm outline-none focus:border-[#234B26]">
                        @for($y = date('Y'); $y >= 2010; $y--)
                            <option value="{{ $y }}" {{ $blankSpot->tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-2">Latitude <span class="text-red-500">*</span></label>
                    <input type="number" name="latitude" step="0.00000001"
                        value="{{ $blankSpot->latitude }}" required
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-3 text-sm outline-none focus:border-[#234B26]">
                </div>

                <div>
                    <label class="block text-[#234B26] font-bold text-sm mb-2">Longitude <span class="text-red-500">*</span></label>
                    <input type="number" name="longitude" step="0.00000001"
                        value="{{ $blankSpot->longitude }}" required
                        class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-3 text-sm outline-none focus:border-[#234B26]">
                </div>
            </div>

            <div>
                <label class="block text-[#234B26] font-bold text-sm mb-2">Keterangan</label>
                <textarea name="keterangan" rows="3"
                    class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-3 text-sm outline-none focus:border-[#234B26] resize-none">{{ $blankSpot->keterangan }}</textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit"
                    class="bg-[#234B26] text-white px-8 py-3 rounded-xl font-semibold hover:bg-[#1a381c] transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('user.dashboard') }}"
                    class="border border-[#234B26] text-[#234B26] px-8 py-3 rounded-xl font-semibold hover:bg-[#D7E3D4] transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
document.getElementById('kecamatan_id').addEventListener('change', function() {
    const id = this.value;
    const desaSelect = document.getElementById('desa_id');
    desaSelect.innerHTML = '<option value="">-- Memuat... --</option>';
    if (!id) return;
    fetch(`/user/api/desa/${id}`)
        .then(r => r.json())
        .then(data => {
            desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
            data.forEach(d => desaSelect.innerHTML += `<option value="${d.id}">${d.nama_desa}</option>`);
        });
});
</script>
@endpush
@endsection