@extends('app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">

    <div class="flex items-center gap-4 mb-8">
        <button onclick="history.back()"
            class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#234B26] text-white hover:bg-[#1a381c]">←</button>
        <h1 class="text-3xl font-bold text-[#234B26]">
            Detail Blank Spot BS-{{ $blankSpot->tahun }}-{{ str_pad($blankSpot->id, 4, '0', STR_PAD_LEFT) }}
        </h1>
    </div>

    <div class="bg-[#F3F3E8] rounded-3xl shadow-xl p-8 grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Info Detail -->
        <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm h-fit">
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26] w-1/3">ID</td>
                        <td class="px-4 py-3 font-mono font-bold">BS-{{ $blankSpot->tahun }}-{{ str_pad($blankSpot->id, 4, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Kabupaten</td>
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
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Longitude</td>
                        <td class="px-4 py-3">{{ $blankSpot->longitude ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Latitude</td>
                        <td class="px-4 py-3">{{ $blankSpot->latitude ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Prioritas</td>
                        <td class="px-4 py-3 font-bold text-amber-800">{{ $blankSpot->prioritas ? 'P' . $blankSpot->prioritas : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Status Jaringan</td>
                        <td class="px-4 py-3">{{ $blankSpot->status_jaringan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Kondisi Geografis</td>
                        <td class="px-4 py-3">{{ $blankSpot->kondisi_geografis ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Jumlah Penduduk</td>
                        <td class="px-4 py-3">{{ $blankSpot->jumlah_penduduk ? (is_numeric($blankSpot->jumlah_penduduk) ? number_format((float)$blankSpot->jumlah_penduduk) . ' Jiwa' : $blankSpot->jumlah_penduduk) : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Jarak ke Ibu Kota (Km)</td>
                        <td class="px-4 py-3">{{ $blankSpot->jarak_ibukota ? $blankSpot->jarak_ibukota . ' Km' : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Tahun</td>
                        <td class="px-4 py-3">{{ $blankSpot->tahun ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Operator</td>
                        <td class="px-4 py-3">{{ $blankSpot->creator->nama ?? $blankSpot->creator->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Tanggal</td>
                        <td class="px-4 py-3">{{ $blankSpot->created_at ? $blankSpot->created_at->format('d M Y, H:i') . ' WIB' : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Status Validasi</td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $blankSpot->status_badge }}">
                                {{ $blankSpot->status_label }}
                            </span>
                        </td>
                    </tr>
                    @if($blankSpot->catatan_revisi)
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Catatan Revisi</td>
                        <td class="px-4 py-3 text-red-600 font-semibold">{{ $blankSpot->catatan_revisi }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Keterangan / Sinyal</td>
                        <td class="px-4 py-3">{{ $blankSpot->keterangan ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Peta -->
        <div class="relative w-full h-[360px] rounded-2xl overflow-hidden border border-gray-300 shadow-inner">
            <div id="detailMap" class="w-full h-full z-10"></div>
        </div>

        <!-- Galeri Foto Dokumentasi -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <h3 class="text-lg font-bold text-[#234B26] mb-4 flex items-center gap-2">
                📷 Galeri Foto Dokumentasi ({{ $blankSpot->photos->count() }})
            </h3>
            @if($blankSpot->photos && $blankSpot->photos->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4">
                    @foreach($blankSpot->photos as $photo)
                        <div class="relative group cursor-pointer overflow-hidden rounded-xl border border-gray-200 shadow-sm aspect-square bg-gray-100"
                             onclick="openLightbox('{{ $photo->url }}')">
                            <img src="{{ $photo->url }}" alt="Foto Blank Spot" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold gap-1">
                                🔍 Perbesar
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm italic">Belum ada foto dokumentasi yang diunggah.</p>
            @endif
        </div>
    </div>

    <div class="flex gap-4 mt-6">
        <a href="{{ route('admin.blank-spot.edit', $blankSpot->id) }}"
            class="bg-[#234B26] text-white px-6 py-3 rounded-xl font-semibold hover:bg-[#1a381c] transition">
            ✏️ Edit Data
        </a>
        <a href="{{ route('admin.blank-spot.index') }}"
            class="border border-[#234B26] text-[#234B26] px-6 py-3 rounded-xl font-semibold hover:bg-[#D7E3D4] transition">
            ← Kembali
        </a>
    </div>
</div>

@push('scripts')
<script>
const map = L.map('detailMap').setView([{{ $blankSpot->latitude }}, {{ $blankSpot->longitude }}], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);
L.marker([{{ $blankSpot->latitude }}, {{ $blankSpot->longitude }}])
    .addTo(map)
    .bindPopup(`<b>{{ $blankSpot->kabupaten->nama_kabupaten ?? '' }}</b><br>{{ $blankSpot->kecamatan->nama_kecamatan ?? '' }}, {{ $blankSpot->desa->nama_desa ?? '' }}`)
    .openPopup();
</script>
@endpush
@endsection