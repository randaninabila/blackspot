@extends('app')

@section('content')
{{-- LEAFTLET CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="max-w-7xl mx-auto px-4 py-10">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-[#234B26]">Data Blank Spot</h1>
        <a href="{{ route('user.blank-spot.create') }}"
            class="bg-[#234B26] text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-[#1a381c] transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Data
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
            ❌ {{ session('error') }}
        </div>
    @endif

    <!-- Tabel -->
    <div class="bg-[#F3F3E8] rounded-3xl shadow-xl p-8 overflow-x-auto">
        <table class="w-full text-sm text-left text-[#234B26]">
            <thead class="border-b-2 border-[#234B26] bg-[#D7E3D4]">
                <tr>
                    <th class="px-4 py-3 text-center font-bold">No</th>
                    <th class="px-4 py-3 font-bold">Kecamatan</th>
                    <th class="px-4 py-3 font-bold">Desa</th>
                    <th class="px-4 py-3 font-bold">Latitude</th>
                    <th class="px-4 py-3 font-bold">Longitude</th>
                    <th class="px-4 py-3 text-center font-bold">Tahun</th>
                    <th class="px-4 py-3 text-center font-bold">Status</th>
                    <th class="px-4 py-3 text-center font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blankSpots as $i => $spot)
               <tr 
    class="border-b border-gray-200 hover:bg-[#F3F3E8]/50 transition cursor-pointer"
    onclick="showDetail(this)"
    data-id="{{ $spot->id }}"
    data-kabupaten="{{ $spot->kecamatan->kabupaten->nama_kabupaten ?? '-' }}"
    data-kecamatan="{{ $spot->kecamatan->nama_kecamatan ?? '-' }}"
    data-desa="{{ $spot->desa->nama_desa ?? '-' }}"
    data-latitude="{{ $spot->latitude }}"
    data-longitude="{{ $spot->longitude }}"
    data-status="{{ $spot->status_validasi }}"
    data-operator="{{ $spot->creator->nama ?? '-' }}"
    data-tanggal="{{ $spot->created_at }}"
    data-keterangan="{{ $spot->keterangan ?? '-' }}"
>
                    <td class="px-4 py-3 text-center">{{ $blankSpots->firstItem() + $i }}</td>
                    <td class="px-4 py-3">{{ $spot->kecamatan->nama_kecamatan ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $spot->desa->nama_desa ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $spot->latitude }}</td>
                    <td class="px-4 py-3">{{ $spot->longitude }}</td>
                    <td class="px-4 py-3 text-center">{{ $spot->tahun }}</td>
                            <td class="px-4 py-3 text-center">
    <div class="flex justify-center items-center">
        <span class="px-2 py-1 rounded-full text-xs font-bold 
            {{ $spot->status_validasi == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
            {{ $spot->status_validasi == 'approved' ? 'bg-green-100 text-green-700' : '' }}
            {{ $spot->status_validasi == 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
            {{ ucfirst($spot->status_validasi) }}
        </span>
    </div>
</td>
                    <td class="px-4 py-3">
                        <div class="flex justify-center gap-2">
                        
                            @if($spot->status_validasi != 'approved')
                            <!-- Edit -->
                            <a href="{{ route('user.blank-spot.edit', $spot->id) }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.8"
        stroke="currentColor"
        class="w-4 h-4">
        <path stroke-linecap="round"
            stroke-linejoin="round"
            d="M16.862 4.487a2.25 2.25 0 113.182 3.182L7.5 20.213 3 21l.787-4.5L16.862 4.487z" />
    </svg>
                            </a>
                            <!-- Hapus -->
                            <form action="{{ route('user.blank-spot.destroy', $spot->id) }}" method="POST"
                                onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-8 text-gray-400">
                        Belum ada data. Klik Tambah Data untuk mulai
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6 flex justify-between items-center">
            <p class="text-sm text-gray-500">
                Menampilkan {{ $blankSpots->firstItem() ?? 0 }} - {{ $blankSpots->lastItem() ?? 0 }}
                dari {{ $blankSpots->total() }} data
            </p>
            {{ $blankSpots->links() }}
        </div>

    </div>

   <div id="detailSection" 
     class="bg-[#F3F3E8] rounded-[2rem] p-6 md:p-8 border border-gray-200/40 shadow-xl hidden mt-8">

    <h4 class="text-[#234B26] font-bold text-2xl mb-6 border-b border-gray-300/60 pb-3">
        Detail Data Blankspot
    </h4>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- DETAIL TABLE --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm h-fit">
            <table class="w-full text-sm text-left">
                <tbody class="divide-y divide-gray-200">

                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">ID</td><td id="detail-id" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Kabupaten</td><td id="detail-kabupaten" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Kecamatan</td><td id="detail-kecamatan" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Desa</td><td id="detail-desa" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Koordinat</td><td id="detail-koordinat" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Status</td><td id="detail-status" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Operator</td><td id="detail-operator" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Tanggal</td><td id="detail-tanggal" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Keterangan</td><td id="detail-keterangan" class="px-4 py-3">-</td></tr>

                </tbody>
            </table>
        </div>

        {{-- MAP OPENSTREETMAP --}}
        <div class="w-full h-[320px] rounded-2xl overflow-hidden border shadow-inner">
            <div id="validasiMap" class="w-full h-full"></div>
        </div>

    </div>
</div>
</div>

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

<script>
let map;
let marker;

function initMap(lat, lng) {
    // Cek apakah Leaflet sudah terload
    if (typeof L === 'undefined') {
        console.error('Leaflet tidak terload!');
        return;
    }

    // kalau map sudah ada, hapus dulu
    if (map) {
        map.remove();
    }

    map = L.map('validasiMap').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    marker = L.marker([lat, lng]).addTo(map)
        .bindPopup("Lokasi Blankspot")
        .openPopup();
}

function showDetail(row) {

    const data = row.dataset;

    const lat = parseFloat(data.latitude);
    const lng = parseFloat(data.longitude);

    // tampilkan section
    document.getElementById('detailSection').classList.remove('hidden');

    // isi data
    document.getElementById('detail-id').innerText = data.id;
    document.getElementById('detail-kabupaten').innerText = data.kabupaten;
    document.getElementById('detail-kecamatan').innerText = data.kecamatan;
    document.getElementById('detail-desa').innerText = data.desa;

    document.getElementById('detail-koordinat').innerText = lat + ', ' + lng;
    document.getElementById('detail-status').innerText = data.status;
    document.getElementById('detail-operator').innerText = data.operator;
    document.getElementById('detail-tanggal').innerText = data.tanggal;
    document.getElementById('detail-keterangan').innerText = data.keterangan;

    // init map
    setTimeout(() => {
        initMap(lat, lng);
    }, 200);

    // scroll ke detail
    document.getElementById('detailSection')
        .scrollIntoView({ behavior: 'smooth' });
}
</script>

@endsection