@extends('app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold text-[#234B26] mb-8">Peta Geospasial Blank Spot</h1>

    <div class="flex flex-col lg:flex-row-reverse gap-6">

        <!-- Panel Kanan: Daftar Kabupaten -->
        <div class="w-full lg:w-1/4 bg-[#F3F3E8] rounded-3xl p-6 shadow-xl border border-[#234B26]/10 space-y-4">
            <h3 class="text-[#234B26] font-bold text-lg border-b border-[#234B26]/20 pb-3">
                Kabupaten/Kota
            </h3>
            <div id="infoPanelKabupaten" class="hidden bg-white rounded-2xl p-4 border border-gray-200">
                <div id="infoPanelContent"></div>
            </div>
            <div class="space-y-2 max-h-[400px] overflow-y-auto pr-1">
                <button onclick="resetPeta()"
                    class="w-full text-left bg-[#234B26] text-[#E6EB9C] px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#1a381c] transition">
                    🗺️ Tampilkan Semua
                </button>
                @foreach($kabupatens as $kab)
                <button onclick="filterKabupaten({{ $kab->id }})"
                    data-id="{{ $kab->id }}"
                    class="btn-kabupaten w-full text-left bg-white border border-[#234B26]/20 text-[#234B26] px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-[#D7E3D4] transition flex justify-between items-center">
                    <span>{{ $kab->nama_kabupaten }}</span>
                    <span class="text-xs bg-[#234B26] text-white px-2 py-0.5 rounded-full">{{ $kab->blank_spots_count }}</span>
                </button>
                @endforeach
            </div>
        </div>

        <!-- Peta -->
        <div class="w-full lg:w-3/4 bg-[#F3F3E8] rounded-3xl p-4 shadow-xl border border-[#234B26]/10">
            <div id="geoMap" class="w-full h-[550px] rounded-2xl z-10 shadow-inner"></div>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
const allSpots = @json($allSpots->map(fn($s) => [
    'id'        => $s->id,
    'lat'       => $s->latitude,
    'lng'       => $s->longitude,
    'kabupaten' => $s->kabupaten->nama_kabupaten ?? '-',
    'kecamatan' => $s->kecamatan->nama_kecamatan ?? '-',
    'desa'      => $s->desa->nama_desa ?? '-',
    'tahun'     => $s->tahun,
    'kab_id'    => $s->kabupaten_id,
    'status'    => $s->status_validasi ?? 'pending', // TAMBAHKAN STATUS
]));

// ... kode selanjutnya sama seperti sebelumnya ...
</script>
@endpush

<script>
const allSpots = @json($allSpots->map(fn($s) => [
    'id'        => $s->id,
    'lat'       => $s->latitude,
    'lng'       => $s->longitude,
    'kabupaten' => $s->kabupaten->nama_kabupaten ?? '-',
    'kecamatan' => $s->kecamatan->nama_kecamatan ?? '-',
    'desa'      => $s->desa->nama_desa ?? '-',
    'tahun'     => $s->tahun,
    'kab_id'    => $s->kabupaten_id,
]));

const map = L.map('geoMap').setView([2.1, 99.5], 8);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

let markersLayer = L.layerGroup().addTo(map);

function renderMarkers(spots) {
    markersLayer.clearLayers();
    spots.forEach(s => {
        L.marker([s.lat, s.lng])
            .addTo(markersLayer)
            .bindPopup(`<b>${s.kabupaten}</b><br>Kec: ${s.kecamatan}<br>Desa: ${s.desa}<br>Tahun: ${s.tahun}`);
    });
}

renderMarkers(allSpots);

function resetPeta() {
    renderMarkers(allSpots);
    map.setView([2.1, 99.5], 8);
    document.getElementById('infoPanelKabupaten').classList.add('hidden');
}

function filterKabupaten(kabId) {
    const panel = document.getElementById('infoPanelKabupaten');
    const content = document.getElementById('infoPanelContent');
    content.innerHTML = '<p class="text-center text-sm text-gray-400 py-3">Memuat...</p>';
    panel.classList.remove('hidden');

    fetch(`/admin/api/kabupaten/${kabId}/data`)
        .then(r => r.json())
        .then(data => {
            renderMarkers(data.spots.map(s => ({...s, kabupaten: data.kabupaten})));

            if (data.spots.length > 0) {
                const bounds = data.spots.map(s => [s.lat, s.lng]);
                map.fitBounds(bounds, { padding: [40, 40] });
            }

            content.innerHTML = `
                <h4 class="font-bold text-[#234B26] text-base mb-3">${data.kabupaten}</h4>
                <div class="grid grid-cols-2 gap-3 text-center mb-3">
                    <div class="bg-[#234B26] text-[#E6EB9C] rounded-xl p-2">
                        <div class="text-2xl font-bold">${data.total}</div>
                        <div class="text-xs">Total Spot</div>
                    </div>
                    <div class="bg-blue-700 text-white rounded-xl p-2">
                        <div class="text-2xl font-bold">${data.desa_terdampak}</div>
                        <div class="text-xs">Desa</div>
                    </div>
                </div>
            `;
        })
        .catch(() => {
            content.innerHTML = '<p class="text-red-500 text-sm text-center py-2">Gagal memuat data.</p>';
        });
}
</script>
@endpush
@endsection