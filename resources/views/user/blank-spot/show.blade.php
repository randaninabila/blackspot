@extends('app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">

    <div class="flex items-center gap-4 mb-8">
        <button onclick="history.back()"
            class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#234B26] text-white hover:bg-[#1a381c]">←</button>
        <h1 class="text-3xl font-bold text-[#234B26]">Detail Laporan Saya</h1>
    </div>

    <div class="bg-[#F3F3E8] rounded-3xl shadow-xl p-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm h-fit">
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26] w-1/3">ID</td>
                        <td class="px-4 py-3 font-mono font-bold">BS-{{ $blankSpot->tahun }}-{{ str_pad($blankSpot->id, 4, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Kabupaten/Kota</td>
                        <td class="px-4 py-3">{{ $blankSpot->kabupaten->nama_kabupaten ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Kecamatan</td>
                        <td class="px-4 py-3">{{ $blankSpot->kecamatan->nama_kecamatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Desa</td>
                        <td class="px-4 py-3">{{ $blankSpot->desa->nama_desa ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Koordinat</td>
                        <td class="px-4 py-3">{{ $blankSpot->latitude }}, {{ $blankSpot->longitude }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Tahun</td>
                        <td class="px-4 py-3">{{ $blankSpot->tahun }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Status Validasi</td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $blankSpot->status_badge }}">
                                {{ $blankSpot->status_label }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Tanggal Input</td>
                        <td class="px-4 py-3">{{ $blankSpot->created_at->format('d M Y, H:i') }} WIB</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Keterangan</td>
                        <td class="px-4 py-3">{{ $blankSpot->keterangan ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="relative w-full h-[300px] rounded-2xl overflow-hidden border border-gray-300 shadow-inner">
            <div id="spotMap" class="w-full h-full z-10"></div>
        </div>
    </div>

    <div class="flex gap-4 mt-6">
        @if($blankSpot->status_validasi !== 'approved')
        <a href="{{ route('user.blank-spot.edit', $blankSpot->id) }}"
            class="bg-yellow-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-yellow-600 transition">
            Edit
        </a>
        @endif
        <a href="{{ route('user.dashboard') }}"
            class="border border-[#234B26] text-[#234B26] px-6 py-3 rounded-xl font-semibold hover:bg-[#D7E3D4] transition">
            ← Kembali
        </a>
    </div>
</div>

@push('scripts')
<script>
const smap = L.map('spotMap').setView([{{ $blankSpot->latitude }}, {{ $blankSpot->longitude }}], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(smap);
L.marker([{{ $blankSpot->latitude }}, {{ $blankSpot->longitude }}])
    .addTo(smap)
    .bindPopup(`<b>{{ $blankSpot->kecamatan->nama_kecamatan ?? '' }}</b><br>{{ $blankSpot->desa->nama_desa ?? '' }}`)
    .openPopup();
</script>
@endpush
@endsection