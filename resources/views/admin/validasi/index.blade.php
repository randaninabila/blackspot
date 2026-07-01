@extends('app')

@section('content')

<section class="max-w-7xl mx-auto py-16 px-8">

    <!-- Hero -->
    <div class="text-center mt-14">
        <h1 class="text-4xl font-bold text-[#234B26] uppercase">
            Data Area Blankspot Provinsi Sumatera Utara
        </h1>
        <h2 class="text-2xl font-bold mt-1 text-[#234B26]">
            Dinas Komunikasi Informatika dan Statistik
        </h2>
        <p class="max-w-4xl mx-auto mt-6 text-gray-700">
            Blank spot merupakan wilayah yang berada di luar 
            cakupan jaringan komunikasi, sehingga sinyal tidak dapat diterima secara optimal. 
            Fenomena ini dapat terjadi pada berbagai jenis layanan komunikasi, baik analog (telepon) maupun digital (internet).
        </p>
    </div>

    <!-- Statistik -->
    <div class="grid md:grid-cols-4 gap-6 mt-28">
        <div class="bg-[#234B26] text-[#E6EB9C] rounded-2xl p-6">
            <p class="font-semibold">Total Data</p>
            <h3 class="text-5xl font-bold my-2">{{ $totalData ?? 0 }}</h3>
            <p class="font-semibold">Data Keseluruhan</p>
        </div>
        <div class="bg-[#234B26] text-[#E6EB9C] rounded-2xl p-6">
            <p class="font-semibold">Nilai Rata-rata Tiap Tahun</p>
            <h3 class="text-5xl font-bold my-2">{{ $nilaiRataRata ?? 0 }}</h3>
            <p class="font-semibold">4 Tahun Terakhir</p>
        </div>
        <div class="bg-[#234B26] text-[#E6EB9C] rounded-2xl p-6">
            <p class="font-semibold">Nilai Tertinggi</p>
            <h3 class="text-5xl font-bold my-2">{{ $nilaiTertinggi ?? 0 }}</h3>
            <p class="font-semibold">Tahun {{ $tahunTertinggi ?? '-' }}</p>
        </div>
        <div class="bg-[#234B26] text-[#E6EB9C] rounded-2xl p-6">
            <p class="font-semibold">Nilai Terendah</p>
            <h3 class="text-5xl font-bold my-2">{{ $nilaiTerendah ?? 0 }}</h3>
            <p class="font-semibold">Tahun {{ $tahunTerendah ?? '-' }}</p>
        </div>
    </div>

    <!-- TOP ACTION -->
    <div class="flex justify-between items-center mt-10">
        <div class="flex border border-[#234B26] rounded-2xl overflow-hidden">
            <button onclick="window.location.href='{{ route('admin.dashboard') }}#table'" class="tab-btn inactive-tab px-14 py-3 font-semibold border-r border-[#234B26]">Tabel</button>
            <button onclick="window.location.href='{{ route('admin.dashboard') }}#grafik'" class="tab-btn inactive-tab px-14 py-3 font-semibold border-r border-[#234B26]">Grafik</button>
            <button onclick="window.location.href='{{ route('admin.dashboard') }}#geo'" class="tab-btn inactive-tab px-14 py-3 font-semibold border-r border-[#234B26]">Geospasial</button>
            <button class="tab-btn active-tab px-14 py-3 font-semibold">Validasi</button>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.add') }}" class="bg-[#234B26] text-white px-6 py-3 rounded-xl font-medium hover:opacity-90">+ Tambah Data</a>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="bg-[#234B26] text-white px-6 py-3 rounded-xl font-medium hover:opacity-90 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v10m0 0l-4-4m4 4l4-4m-9 8h10" />
                    </svg>
                    <span>Download</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 bg-white rounded-xl shadow-xl border border-gray-200 z-50 overflow-hidden min-w-[160px]">
                    <a href="{{ route('admin.export.pdf') }}" class="flex items-center gap-2 px-4 py-3 text-[#234B26] hover:bg-[#D7E3D4] text-sm font-medium">📄 Export PDF</a>
                    <a href="{{ route('admin.export.excel') }}" class="flex items-center gap-2 px-4 py-3 text-[#234B26] hover:bg-[#D7E3D4] text-sm font-medium">📊 Export Excel</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================== -->
    <!-- TAB VALIDASI -->
    <!-- ======================================== -->
    <div id="content-validasi" class="mt-8">
        
        <!-- Statistik Validasi -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-[#FEF9C3] border border-yellow-200 rounded-2xl p-6 shadow-sm transition-all hover:shadow-md">
                <p class="font-semibold text-[20px]">Menunggu Validasi</p>
                <h3 class="text-5xl font-bold my-2" data-counter="menunggu">{{ $totalMenunggu ?? 0 }}</h3>
                <p class="font-semibold text-[20px]">Data</p>
            </div>
            <div class="bg-[#DCFCE7] border border-green-200 rounded-2xl p-6 shadow-sm transition-all hover:shadow-md">
                <p class="font-semibold text-[20px]">Disetujui</p>
                <h3 class="text-5xl font-bold my-2" data-counter="disetujui">{{ $totalDisetujui ?? 0 }}</h3>
                <p class="font-semibold text-[20px]">Data</p>
            </div>
            <div class="bg-[#FCE7F3] border border-pink-200 rounded-2xl p-6 text-black shadow-sm transition-all hover:shadow-md">
                <p class="font-semibold text-[20px]">Ditolak</p>
                <h3 class="text-5xl font-bold my-2" data-counter="ditolak">{{ $totalDitolak ?? 0 }}</h3>
                <p class="font-semibold text-[20px]">Data</p>
            </div>
        </div>

        <!-- Filter Validasi -->
        <div class="bg-white rounded-2xl p-6 mb-6 border border-gray-200">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-[#234B26] text-xs font-bold mb-1.5 pl-1">Kab/Kota</label>
                    <select id="filterKabupaten" 
                            class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all appearance-none">
                        <option value="">Semua Kab/Kota</option>
                        @foreach($kabupatens ?? [] as $kab)
                            <option value="{{ $kab->id }}">{{ $kab->nama_kabupaten }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[#234B26] text-xs font-bold mb-1.5 pl-1">Status Validasi</label>
                    <select id="filterStatus" 
                            class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all appearance-none">
                        <option value="pending" selected>Menunggu Validasi</option>
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[#234B26] text-xs font-bold mb-1.5 pl-1">Tahun</label>
                    <select id="filterTahun" 
                            class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all appearance-none">
                        <option value="">Semua Tahun</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[#234B26] text-xs font-bold mb-1.5 pl-1">Cari Lokasi</label>
                    <input type="text" id="filterCari" placeholder="Cari desa, kecamatan..." 
                           class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#234B26] transition-all placeholder:text-gray-400">
                </div>
            </div>
            <div class="mt-4 flex gap-3">
                <button onclick="filterValidasiTable()" class="bg-[#234B26] text-white px-6 py-2 rounded-xl text-sm font-semibold hover:bg-[#1a381c] transition">Filter</button>
                <button onclick="resetFilterValidasi()" class="border border-[#234B26] text-[#234B26] px-6 py-2 rounded-xl text-sm font-semibold hover:bg-[#D7E3D4] transition">Reset</button>
            </div>
        </div>

        <h4 class="text-[#234B26] font-bold text-2xl mb-6 border-b border-gray-300/60 pb-3">
            Daftar Data Menunggu Validasi
        </h4>

        <!-- Tabel Validasi -->
        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-50 border-b border-gray-200 text-[#234B26] font-bold">
                    <tr>
                        <th class="px-4 py-3.5 text-center">No</th>
                        <th class="px-4 py-3.5">ID Data</th>
                        <th class="px-4 py-3.5">Kab/Kota</th>
                        <th class="px-4 py-3.5">Kecamatan</th>
                        <th class="px-4 py-3.5">Desa</th>
                        <th class="px-4 py-3.5">Status Jaringan</th>
                        <th class="px-4 py-3.5 text-center">Tahun</th>
                        <th class="px-4 py-3.5">Tanggal Input</th>
                        <th class="px-4 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="validasiTableBody">
                    @forelse($validasiMenunggu ?? [] as $i => $spot)
                    <tr class="data-row hover:bg-gray-50/80 cursor-pointer transition-colors"
                        id="row-{{ $spot->id }}"
                        data-id="{{ $spot->id }}"
                        data-kabupaten="{{ $spot->kabupaten->nama_kabupaten ?? '-' }}"
                        data-kecamatan="{{ $spot->kecamatan->nama_kecamatan ?? '-' }}"
                        data-desa="{{ $spot->desa->nama_desa ?? '-' }}"
                        data-lat="{{ $spot->latitude }}"
                        data-lng="{{ $spot->longitude }}"
                        data-status="{{ $spot->keterangan ?? 'Blank Spot' }}"
                        data-operator="{{ $spot->creator->nama ?? '-' }}"
                        data-tanggal="{{ $spot->created_at->format('d M Y, H:i') }} WIB"
                        data-keterangan="{{ $spot->keterangan ?? '-' }}"
                        data-status-validasi="{{ $spot->status_validasi }}"
                        data-kabupaten-id="{{ $spot->kabupaten_id }}"
                        data-tahun="{{ $spot->tahun }}"
                        onclick="pilihRow(this)">

                        <td class="px-4 py-3.5 text-center font-medium">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3.5 font-mono text-xs font-bold text-gray-900">BS-{{ $spot->tahun }}-{{ str_pad($spot->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3.5">{{ $spot->kabupaten->nama_kabupaten ?? '-' }}</td>
                        <td class="px-4 py-3.5">{{ $spot->kecamatan->nama_kecamatan ?? '-' }}</td>
                        <td class="px-4 py-3.5">{{ $spot->desa->nama_desa ?? '-' }}</td>
                        <td class="px-4 py-3.5">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full
                                @if(str_contains(strtolower($spot->keterangan ?? ''), 'lemah')) bg-yellow-100 text-yellow-700
                                @elseif(str_contains(strtolower($spot->keterangan ?? ''), 'stabil')) bg-blue-100 text-blue-700
                                @else bg-red-100 text-red-600 @endif">
                                {{ $spot->keterangan ?? 'Tidak Ada Sinyal' }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-center">{{ $spot->tahun }}</td>
                        <td class="px-4 py-3.5 text-gray-500">{{ $spot->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3.5 aksi-cell" data-id="{{ $spot->id }}">
                            <div class="flex justify-center gap-2">
                                <!-- EDIT -->
                                <a href="{{ route('admin.validasi.edit', $spot->id) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.25 2.25 0 113.182 3.182L7.5 20.213 3 21l.787-4.5L16.862 4.487z" />
                                    </svg>
                                </a>
                                <!-- HAPUS -->
                                <button onclick="event.stopPropagation(); hapusData({{ $spot->id }})"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M10 11v6M14 11v6M5 7l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="9" class="text-center py-8 text-gray-400">Tidak ada data yang menunggu validasi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Detail Section -->
        <div id="detailSection" class="bg-[#F3F3E8] rounded-[2rem] p-6 md:p-8 border border-gray-200/40 shadow-xl hidden mt-6">
            <h4 class="text-[#234B26] font-bold text-2xl mb-6 border-b border-gray-300/60 pb-3">Detail Data Blankspot</h4>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm h-fit">
                    <table class="w-full text-sm text-left border-collapse">
                        <tbody class="divide-y divide-gray-200">
                            <tr><td class="w-1/3 bg-gray-50 px-4 py-3 font-bold text-[#234B26]">ID Data</td><td id="detail-id" class="px-4 py-3 font-semibold">-</td></tr>
                            <tr><td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Kab/Kota</td><td id="detail-kabupaten" class="px-4 py-3">-</td></tr>
                            <tr><td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Kecamatan</td><td id="detail-kecamatan" class="px-4 py-3">-</td></tr>
                            <tr><td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Desa</td><td id="detail-desa" class="px-4 py-3">-</td></tr>
                            <tr><td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Koordinat</td><td id="detail-koordinat" class="px-4 py-3">-</td></tr>
                            <tr><td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Status Jaringan</td><td id="detail-status" class="px-4 py-3">-</td></tr>
                            <tr><td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Diinput Oleh</td><td id="detail-operator" class="px-4 py-3">-</td></tr>
                            <tr><td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Tanggal Input</td><td id="detail-tanggal" class="px-4 py-3">-</td></tr>
                            <tr><td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Keterangan</td><td id="detail-keterangan" class="px-4 py-3">-</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="relative w-full h-[320px] lg:h-full min-h-[300px] bg-gray-200 rounded-2xl overflow-hidden border border-gray-300 shadow-inner">
                    <div id="validasiMap" class="w-full h-full z-10"></div>
                </div>
            </div>
        </div>

        <!-- Tombol Setujui & Tolak -->
        <div class="flex flex-wrap items-center justify-center gap-4 mt-8 pt-6 border-t border-gray-300/60">
            <button type="button" onclick="aksiSetujui()"
                class="flex items-center gap-2 bg-[#234B26] hover:bg-[#1a381c] text-white px-8 py-3.5 rounded-xl font-bold shadow-md transition-transform transform active:scale-95 text-base">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Setujui
            </button>
            <button type="button" onclick="aksiTolak()"
                class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-8 py-3.5 rounded-xl font-bold shadow-md transition-transform transform active:scale-95 text-base">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
                Tolak
            </button>
        </div>

    </div>
    <!-- ======== END TAB VALIDASI ======== -->

</div>
</section>

<style>
.active-tab{
    background:#234B26;
    color:white;
}
.inactive-tab{
    background:white;
    color:#234B26;
}
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
select::-ms-expand { display: none; }
</style>

<script>
/* ============================================================
   FILTER VALIDASI TABLE
============================================================ */
function filterValidasiTable() {
    const kabupaten = document.getElementById('filterKabupaten')?.value || '';
    const status = document.getElementById('filterStatus')?.value || '';
    const tahun = document.getElementById('filterTahun')?.value || '';
    const cari = document.getElementById('filterCari')?.value?.toLowerCase() || '';

    document.querySelectorAll('#validasiTableBody tr.data-row').forEach(function(row) {
        const matchKab = !kabupaten || row.dataset.kabupatenId === kabupaten;
        const matchStatus = !status || status === 'all' || row.dataset.statusValidasi === status;
        const matchTahun = !tahun || row.dataset.tahun === tahun;
        const matchCari = !cari || 
            row.dataset.kabupaten?.toLowerCase().includes(cari) ||
            row.dataset.kecamatan?.toLowerCase().includes(cari) ||
            row.dataset.desa?.toLowerCase().includes(cari);

        row.style.display = (matchKab && matchStatus && matchTahun && matchCari) ? '' : 'none';
    });
}

function resetFilterValidasi() {
    document.getElementById('filterKabupaten').value = '';
    document.getElementById('filterStatus').value = 'pending';
    document.getElementById('filterTahun').value = '';
    document.getElementById('filterCari').value = '';
    filterValidasiTable();
}

document.addEventListener('DOMContentLoaded', function() {
    const filterKab = document.getElementById('filterKabupaten');
    const filterStatus = document.getElementById('filterStatus');
    const filterTahun = document.getElementById('filterTahun');
    const filterCari = document.getElementById('filterCari');

    if (filterKab) filterKab.addEventListener('change', filterValidasiTable);
    if (filterStatus) filterStatus.addEventListener('change', filterValidasiTable);
    if (filterTahun) filterTahun.addEventListener('change', filterValidasiTable);
    if (filterCari) filterCari.addEventListener('keyup', filterValidasiTable);
});

/* ============================================================
   VALIDASI - PILIH ROW & DETAIL
============================================================ */
let activeSpotId = null;

function pilihRow(row) {
    document.querySelectorAll('.data-row').forEach(function(r) {
        r.classList.remove('bg-green-100', 'border-l-4', 'border-green-700');
    });
    row.classList.add('bg-green-100', 'border-l-4', 'border-green-700');
    
    activeSpotId = row.dataset.id;

    document.getElementById('detailSection')?.classList.remove('hidden');
    document.getElementById('detail-id').textContent = row.dataset.id;
    document.getElementById('detail-kabupaten').textContent = row.dataset.kabupaten;
    document.getElementById('detail-kecamatan').textContent = row.dataset.kecamatan;
    document.getElementById('detail-desa').textContent = row.dataset.desa;
    document.getElementById('detail-koordinat').textContent = row.dataset.lat + ', ' + row.dataset.lng;
    document.getElementById('detail-status').innerHTML = '<span class="px-2 py-1 text-xs font-bold bg-red-100 text-red-600 rounded">' + row.dataset.status + '</span>';
    document.getElementById('detail-operator').textContent = row.dataset.operator;
    document.getElementById('detail-tanggal').textContent = row.dataset.tanggal;
    document.getElementById('detail-keterangan').textContent = row.dataset.keterangan;

    updateValidasiMap(row.dataset.lat, row.dataset.lng, row.dataset.id, row.dataset.status);
    document.getElementById('detailSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/* ============================================================
   VALIDASI MAP
============================================================ */
let validasiMap = null;
let detailMarker = null;

function initValidasiMap() {
    if (validasiMap !== null) {
        setTimeout(function() { validasiMap.invalidateSize(); }, 100);
        return;
    }
    validasiMap = L.map('validasiMap').setView([3.5952, 98.6722], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(validasiMap);
}

function updateValidasiMap(lat, lng, id, status) {
    lat = parseFloat(lat);
    lng = parseFloat(lng);
    if (!validasiMap) { initValidasiMap(); }
    if (detailMarker) { validasiMap.removeLayer(detailMarker); }
    detailMarker = L.marker([lat, lng]).addTo(validasiMap).bindPopup('<b>' + id + '</b><br>' + status).openPopup();
    validasiMap.setView([lat, lng], 15);
    setTimeout(function() { validasiMap.invalidateSize(); }, 200);
}

/* ============================================================
   AKSI VALIDASI - SETUJUI & TOLAK
============================================================ */
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function aksiSetujui() {
    if (!activeSpotId) {
        alert('Pilih data terlebih dahulu dengan mengklik baris pada tabel.');
        return;
    }
    if (!confirm('Setujui data ini?')) return;

    fetch('/admin/validasi/' + activeSpotId + '/setujui', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById('row-' + activeSpotId);
            if (row) { row.style.display = 'none'; }
            const menunggu = document.querySelector('[data-counter="menunggu"]');
            if (menunggu) { menunggu.textContent = Math.max(0, parseInt(menunggu.textContent) - 1); }
            const disetujui = document.querySelector('[data-counter="disetujui"]');
            if (disetujui) { disetujui.textContent = parseInt(disetujui.textContent) + 1; }
            document.getElementById('detailSection')?.classList.add('hidden');
            activeSpotId = null;
            alert(data.message || 'Data berhasil disetujui!');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Silakan coba lagi.'));
        }
    })
    .catch(error => { alert('Gagal terhubung ke server.'); });
}

function aksiTolak() {
    if (!activeSpotId) {
        alert('Pilih data terlebih dahulu dengan mengklik baris pada tabel');
        return;
    }
    if (!confirm('Apakah Anda yakin ingin menolak data ini?')) return;

    fetch('/admin/validasi/' + activeSpotId + '/tolak', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById('row-' + activeSpotId);
            if (row) { row.style.display = 'none'; }
            const menunggu = document.querySelector('[data-counter="menunggu"]');
            if (menunggu) { menunggu.textContent = Math.max(0, parseInt(menunggu.textContent) - 1); }
            const ditolak = document.querySelector('[data-counter="ditolak"]');
            if (ditolak) { ditolak.textContent = parseInt(ditolak.textContent) + 1; }
            document.getElementById('detailSection')?.classList.add('hidden');
            activeSpotId = null;
            alert(data.message || 'Data berhasil ditolak!');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Silakan coba lagi'));
        }
    })
    .catch(error => { alert('Gagal terhubung ke server'); });
}

/* ============================================================
   EDIT & HAPUS DATA
============================================================ */
function editData(id) {
    window.location.href = '/admin/validasi/' + id + '/edit';
}

function hapusData(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus data ini secara permanen?')) return;
    fetch('/admin/validasi/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById('row-' + id);
            if (row) { row.style.display = 'none'; }
            const menunggu = document.querySelector('[data-counter="menunggu"]');
            if (menunggu) { menunggu.textContent = Math.max(0, parseInt(menunggu.textContent) - 1); }
            alert(data.message || 'Data berhasil dihapus!');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Silakan coba lagi'));
        }
    })
    .catch(error => { alert('Gagal menghapus data'); });
}
</script>

@endsection