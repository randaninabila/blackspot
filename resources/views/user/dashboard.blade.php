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
     <div class="grid md:grid-cols-3 gap-6 mt-28">

        <div class="bg-[#234B26] text-[#E6EB9C] rounded-2xl p-6">
            <p class="font-semibold">Total Data</p>
            <h3 class="text-5xl font-bold my-2">{{ $totalData ?? 0 }}</h3>
            <p class="font-semibold">Data Keseluruhan</p>
        </div>

        <div class="bg-[#234B26] text-[#E6EB9C] rounded-2xl p-6">
            <p class="font-semibold">Nama Kab/Kota Terbanyak</p>
            <h3 class="text-5xl font-bold my-2">{{ $nilaiTertinggi ?? 0 }}</h3>
            <p class="font-semibold">Tahun {{ $tahunTertinggi ?? '-' }}</p>
        </div>

        <div class="bg-[#234B26] text-[#E6EB9C] rounded-2xl p-6">
            <p class="font-semibold">Nama Kab/Kota Tersedikit</p>
            <h3 class="text-5xl font-bold my-2">{{ $nilaiTerendah ?? 0 }}</h3>
            <p class="font-semibold">Tahun {{ $tahunTerendah ?? '-' }}</p>
        </div>

    </div>

    <!-- TOP ACTION -->
    <div class="flex justify-between items-center mt-10">
        <div class="flex border border-[#234B26] rounded-2xl overflow-hidden">
            <button onclick="switchTab('table')" id="btn-table" class="tab-btn active-tab px-14 py-3 font-semibold border-r border-[#234B26]">Tabel</button>
            <button onclick="switchTab('grafik')" id="btn-grafik" class="tab-btn inactive-tab px-14 py-3 font-semibold border-r border-[#234B26]">Grafik</button>
            <button onclick="switchTab('geo')" id="btn-geo" class="tab-btn inactive-tab px-14 py-3 font-semibold border-r border-[#234B26]">Geopasial</button>
        </div>
        <div class="flex items-center gap-2">
           <a href="{{ route('user.add') }}" class="bg-[#008001] text-white px-6 py-3 rounded-xl font-medium hover:opacity-90">+ Tambah Data</a>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="bg-[#0F2AF4] text-white px-6 py-3 rounded-xl font-medium hover:opacity-90 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v10m0 0l-4-4m4 4l4-4m-9 8h10" />
                    </svg>
                    <span>Download</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 bg-white rounded-xl shadow-xl border border-gray-200 z-50 overflow-hidden min-w-[160px]">
                    <a href="{{ route('user.export.pdf') }}" class="flex items-center gap-2 px-4 py-3 text-[#234B26] hover:bg-[#D7E3D4] text-sm font-medium">Export PDF</a>
                    <a href="{{ route('user.export.excel') }}" class="flex items-center gap-2 px-4 py-3 text-[#234B26] hover:bg-[#D7E3D4] text-sm font-medium"> Export Excel</a>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT TABLE -->
    <div id="content-table" class="tab-content mt-10">
        <div class="bg-[#F3F3E8] rounded-3xl shadow-2xl p-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="text-[#234B26] font-bold text-2xl">Tampilkan</span>
                    <div class="relative w-fit">
                        <select id="entries" onchange="changeEntries()" class="appearance-none bg-[#234B26] text-white pl-3 pr-7 py-1.5 rounded-lg outline-none text-lg font-semibold cursor-pointer">
                            <option selected>10</option>
                            <option>20</option>
                            <option>30</option>
                            <option>40</option>
                            <option>50</option>
                        </select>
                        <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-white text-sm">▼</div>
                    </div>
                    <span class="text-[#234B26] font-bold text-2xl">Data</span>
                </div>
                <div class="relative w-full md:w-80">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-[#234B26]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input id="searchInput" onkeyup="searchTable()" type="text" placeholder="Cari nama kota/kabupaten..." class="w-80 border-2 border-[#234B26] rounded-2xl px-9 py-3 outline-none">
                </div>
            </div>

            <!-- TABLE -->
            <div class="mt-10 overflow-x-auto">
                <table class="w-full text-sm text-left text-[#234B26] border-collapse">
                    <thead class="border-b-2 border-[#234B26] bg-[#D7E3D4]">
                        <tr>
                            <th class="px-4 py-3 text-center font-bold">No</th>
                            <th class="px-4 py-3 font-bold">Nama Kab/Kota</th>
                            <th class="px-4 py-3 font-bold">Nama Kecamatan</th>
                            <th class="px-4 py-3 font-bold">Nama Desa</th>
                            <th class="px-4 py-3 font-bold">Longitude</th>
                            <th class="px-4 py-3 font-bold">Latitude</th>
                            <th class="px-4 py-3 font-bold">Tahun</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($blankSpots as $i => $spot)
                        <tr class="border-b border-gray-200 hover:bg-[#F3F3E8]/50 transition">
                            <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">{{ $spot->kabupaten->nama_kabupaten ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $spot->kecamatan->nama_kecamatan ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $spot->desa->nama_desa ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $spot->longitude }}</td>
                            <td class="px-4 py-3">{{ $spot->latitude }}</td>
                            <td class="px-4 py-3 text-center">{{ $spot->tahun }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-400">Belum ada data blank spot.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 mt-10">
                <p class="text-lg font-medium text-[#234B26]">
                    Menampilkan <span id="showStart">1</span> - <span id="showEnd">10</span> dari <span id="showTotal">0</span> data
                </p>
                <div class="flex items-center gap-2">
                    <button id="prevBtn" onclick="prevPage()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#234B26] text-[#E6EB9C] hover:opacity-90 disabled:opacity-40 disabled:cursor-not-allowed">←</button>
                    <div id="paginationNumbers" class="flex items-center gap-2"></div>
                    <button id="nextBtn" onclick="nextPage()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#234B26] text-[#E6EB9C] hover:opacity-90 disabled:opacity-40 disabled:cursor-not-allowed">→</button>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB GRAFIK -->
    <div id="content-grafik" class="tab-content hidden mt-10">
        <div class="flex flex-col lg:flex-row gap-6">
            <div class="w-full lg:w-1/4 bg-[#F3F3E8] rounded-3xl p-6 shadow-2xl border border-[#234B26]/10 h-fit">
                <label for="chartType" class="block text-[#234B26] font-bold text-xl mb-3.5">Jenis Grafik</label>
                <div class="relative">
                    <select id="chartType" onchange="updateChartType()" class="w-full bg-[#234B26] text-white px-5 py-3 rounded-2xl outline-none text-base font-semibold cursor-pointer appearance-none pr-10 shadow-lg">
                        <option value="bar">Bar Chart</option>
                        <option value="line">Line Chart</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-white font-bold">▼</div>
                </div>
            </div>
            <div class="w-full lg:w-3/4 bg-[#F3F3E8] rounded-3xl p-6 md:p-8 shadow-2xl border border-[#234B26]/10">
                <h3 class="text-[#234B26] font-bold text-2xl text-center mb-6">Data Blankspot Sumatra Utara</h3>
                <div class="relative w-full h-[450px]">
                    <canvas id="blankspotChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB GEOSPASIAL -->
    <div id="content-geo" class="tab-content hidden mt-10">
        <div class="flex flex-col lg:flex-row-reverse gap-6">
            <div class="w-full lg:w-1/4 bg-[#F3F3E8] rounded-3xl p-6 shadow-2xl border border-[#234B26]/10 h-fit space-y-5">
                <div>
                    <label for="geoRegion" class="block text-[#234B26] font-bold text-lg mb-2">Spasial (Wilayah)</label>
                    <div class="relative">
                        <select id="geoRegion" class="w-full bg-[#234B26] text-white px-4 py-3 rounded-xl outline-none text-base font-semibold cursor-pointer appearance-none pr-10">
                            <option value="all">Semua Kabupaten/Kota</option>
                            @foreach($kabupatens ?? [] as $kab)
                                <option value="{{ $kab->id }}">{{ $kab->nama_kabupaten }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-white text-xs">▼</div>
                    </div>
                </div>
                <div>
                    <label for="geoYear" class="block text-[#234B26] font-bold text-lg mb-2">Tahun</label>
                    <div class="relative">
                        <select id="geoYear" class="w-full bg-[#234B26] text-white px-4 py-3 rounded-xl outline-none text-base font-semibold cursor-pointer appearance-none pr-10">
                            <option value="">Pilih Tahun</option>
                            @foreach($tahuns ?? [] as $tahun)
                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-white text-xs">▼</div>
                    </div>
                </div>
                <button onclick="filterGeospatial()" class="w-full bg-[#234B26] text-[#E6EB9C] hover:bg-[#1b3a1d] font-bold py-3 px-4 rounded-xl transition duration-200 shadow-md tracking-wider text-sm mt-2">Pratinjau</button>
            </div>
            <div class="w-full lg:w-3/4 bg-[#F3F3E8] rounded-3xl p-4 shadow-2xl border border-[#234B26]/10">
                <div id="map" class="w-full h-[500px] rounded-2xl z-10 shadow-inner"></div>
            </div>
        </div>
    </div>

</section>

<style>
.active-tab{ background:#234B26; color:white; }
.inactive-tab{ background:white; color:#234B26; }
.active-page{ background:#234B26; color:white; padding:4px 10px; border-radius:6px; }
.page-number{ padding:4px 10px; border-radius:6px; }
.page-btn{ background:#234B26; color:white; width:28px; height:28px; border-radius:999px; }
</style>

<script>
function switchTab(tab){
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('active-tab');
        el.classList.add('inactive-tab');
    });
    document.getElementById('content-' + tab).classList.remove('hidden');
    let btn = document.getElementById('btn-' + tab);
    btn.classList.remove('inactive-tab');
    btn.classList.add('active-tab');
}

function searchTable(){
    let input = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.querySelectorAll("#tableBody tr");
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}

function changeEntries(){
    let selected = document.getElementById("entries").value;
    alert("Menampilkan " + selected + " data");
}

let currentPage = 1;
let perPage = parseInt(document.getElementById("entries").value);

function getRows() {
    return [...document.querySelectorAll("#tableBody tr")].filter(row => row.dataset.filtered !== "hidden");
}

function renderTable() {
    const rows = getRows();
    const totalPages = Math.ceil(rows.length / perPage) || 1;
    if(currentPage > totalPages) currentPage = totalPages;
    document.querySelectorAll("#tableBody tr").forEach(row => row.classList.add("hidden"));
    const start = (currentPage - 1) * perPage;
    const end = start + perPage;
    rows.forEach((row, index) => {
        if(index >= start && index < end) row.classList.remove("hidden");
    });
    renderPagination(rows.length);
    document.getElementById("showTotal").textContent = rows.length;
    document.getElementById("showStart").textContent = rows.length === 0 ? 0 : start + 1;
    document.getElementById("showEnd").textContent = Math.min(end, rows.length);
}

function renderPagination(totalData){
    const totalPages = Math.ceil(totalData / perPage) || 1;
    const container = document.getElementById("paginationNumbers");
    container.innerHTML = "";
    for(let i = 1; i <= totalPages; i++){
        const btn = document.createElement("button");
        btn.innerText = i;
        btn.className = i === currentPage
            ? `w-10 h-10 rounded-xl bg-[#E6EB9C] text-[#234B26] border border-[#234B26] font-bold`
            : `w-10 h-10 rounded-xl border border-[#234B26] text-[#234B26] hover:bg-[#E6EB9C] transition`;
        btn.onclick = () => { currentPage = i; renderTable(); };
        container.appendChild(btn);
    }
    document.getElementById("prevBtn").disabled = currentPage === 1;
    document.getElementById("nextBtn").disabled = currentPage === totalPages;
}

function nextPage(){
    const totalPages = Math.ceil(getRows().length / perPage);
    if(currentPage < totalPages){ currentPage++; renderTable(); }
}

function prevPage(){
    if(currentPage > 1){ currentPage--; renderTable(); }
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("#tableBody tr").forEach(row => { row.dataset.filtered = "show"; });
    renderTable();
});

let myChart = null;
const chartData = {
    labels: @json($grafikLabels ?? []),
    datasets: [{
        label: 'Jumlah Blank Spot',
        data: @json($grafikData ?? []),
        backgroundColor: '#86EFAC',
        borderColor: '#86EFAC',
        borderWidth: 1,
        borderRadius: 8
    }]
};

function initChart(type = 'bar') {
    const ctx = document.getElementById('blankspotChart').getContext('2d');
    if (myChart) { myChart.destroy(); }
    myChart = new Chart(ctx, {
        type: type,
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { color: '#234B26', font: { weight: 'bold', size: 14 }, boxWidth: 20, padding: 20 } },
                tooltip: { padding: 12, cornerRadius: 12 }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#234B26', font: { weight: 'bold', size: 14 } } },
                y: { min: 0, max: 30, ticks: { stepSize: 5, color: '#234B26', font: { size: 12 } }, grid: { color: 'rgba(35, 75, 38, 0.1)' } }
            }
        }
    });
}

function updateChartType() {
    const selectedType = document.getElementById('chartType').value;
    initChart(selectedType);
}

const originalSwitchTab = switchTab;
switchTab = function(tab) {
    originalSwitchTab(tab);
    if (tab === 'grafik') {
        setTimeout(() => { initChart(document.getElementById('chartType').value); }, 50);
    }
    if (tab === 'geo') {
        // Peta baru diinisialisasi di sini karena #map berada di dalam
        // tab-content yang hidden saat page load. Leaflet butuh container
        // yang sudah terlihat & punya ukuran sebelum L.map() dipanggil,
        // jadi initMap() dipanggil setelah tab-content di-unhide.
        setTimeout(() => { initMap(); }, 50);
    }
}

let map = null;
let markersLayer = L.layerGroup();
const spotsData = @json($spotsPeta ?? []);
const blankspotLocations = spotsData.map(function(s) {
    return {
        name: (s.kecamatan ? s.kecamatan.nama_kecamatan : '-') + ', ' + (s.desa ? s.desa.nama_desa : '-'),
        lat: s.latitude,
        lng: s.longitude,
        year: s.tahun,
        kab: s.kabupaten_id
    };
});

function initMap() {
    if (map !== null) { map.invalidateSize(); return; }
    map = L.map('map').setView([3.5952, 98.6722], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
    markersLayer.addTo(map);
    renderMarkers();
}

function renderMarkers(locations) {
    const data = locations || blankspotLocations;
    markersLayer.clearLayers();
    data.forEach(loc => {
        if (loc.lat && loc.lng) {
            L.marker([loc.lat, loc.lng]).addTo(markersLayer).bindPopup('<b>' + loc.name + '</b><br>Tahun: ' + loc.year);
        }
    });
}

function filterGeospatial() {
    const region = document.getElementById("geoRegion").value;
    const year = document.getElementById("geoYear").value;

    if (!map) {
        alert('Peta belum siap. Silakan tunggu sebentar');
        return;
    }

    // Tampilkan loading
    const btn = document.querySelector('button[onclick="filterGeospatial()"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = 'Memuat...';
    btn.disabled = true;

    // Panggil API user filter
    fetch(`/user/api/filter-geospasial?kabupaten_id=${region}&tahun=${year}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const filteredLocations = data.data.map(function(s) {
                    return {
                        name: (s.kecamatan ? s.kecamatan : '-') + ', ' + (s.desa ? s.desa : '-'),
                        lat: s.lat,
                        lng: s.lng,
                        year: s.tahun,
                        status: s.keterangan || 'Blank Spot',
                        kab: s.kabupaten_id
                    };
                });
                renderMarkers(filteredLocations);

                if (filteredLocations.length > 0) {
                    var bounds = filteredLocations.map(function(loc) {
                        return [loc.lat, loc.lng];
                    });
                    map.fitBounds(bounds, { padding: [50, 50] });
                } else {
                    alert('Tidak ada data untuk filter yang dipilih');
                }
            } else {
                alert('Gagal memuat data untuk filter ini');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memfilter data');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
}
</script>

@endsection