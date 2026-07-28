@extends('app')

@section('content')

<section class="max-w-7xl mx-auto py-16 px-8">

    <!-- Hero -->
    <div class="text-center mt-14">
        <h1 class="text-4xl font-bold text-[#234B26] uppercase">
            Data Area Blankspot Provinsi Sumatera Utara
        </h1>
        <h2 class="text-2xl font-bold mt-1 text-[#234B26]">
            Dinas Komunikasi dan Informatika 
        </h2>
        <p class="max-w-4xl mx-auto mt-6 text-gray-700">
            Blank spot merupakan wilayah yang berada di luar 
            cakupan jaringan komunikasi, sehingga sinyal tidak dapat diterima secara optimal. 
            Fenomena ini dapat terjadi pada berbagai jenis layanan komunikasi, baik analog (telepon) maupun digital (internet).
        </p>
    </div>

    <!-- STATISTIK -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-28 mx-50">

    <!-- Total Data (1/4) -->
    <div class="md:col-span-1 bg-[#234B26] text-[#E6EB9C] rounded-2xl p-6 min-h-[150px] flex flex-col justify-center">
        <p class="font-semibold">
            Total Data
        </p>

        <h3 class="text-5xl font-bold my-2">
            120
        </h3>

        <p class="font-semibold">
            Data Keseluruhan
        </p>
    </div>
   

    <!-- Kabupaten/Kota Terbanyak (3/4) -->
    <div class="md:col-span-2 bg-[#234B26] text-[#E6EB9C] rounded-2xl p-6 min-h-[150px] flex flex-col justify-center">
        <p class="font-semibold">
            Kab/Kota dengan Area Blankspot Terbanyak
        </p>

        <h3 class="text-5xl font-bold my-2">
            Kab Johor
        </h3>

        <p class="font-semibold">
            Tahun 2026
        </p>
    </div>

</div>

    <!-- TOP ACTION -->
    <div class="flex justify-between items-center mt-10">
        <div class="flex border border-[#234B26] rounded-2xl overflow-hidden">
            <button onclick="switchTab('table')" id="btn-table" class="tab-btn active-tab px-14 py-3 font-semibold border-r border-[#234B26]">Tabel</button>
            <button onclick="switchTab('geo')" id="btn-geo" class="tab-btn inactive-tab px-14 py-3 font-semibold border-r border-[#234B26]">Geopasial</button>
        </div>
    </div>

    <!-- CONTENT TABLE -->
    <div id="content-table" class="tab-content mt-10">

    <!-- GRAFIK DASHBOARD -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-10">

    <!-- BAR CHART -->
    <div class="lg:col-span-2 bg-[#F3F3E8] rounded-3xl shadow-2xl p-8">

        <h2 class="text-2xl font-bold text-[#234B26] mb-6">
            Data Blankspot Sumatra Utara
        </h2>

        <div class="relative h-[360px]">
            <canvas id="blankspotBarChart"></canvas>
        </div>

    </div>

    <!-- PIE CHART -->
    <div class="bg-[#234B26] rounded-3xl shadow-2xl p-8 h-[550px] overflow-hidden">

        <h2 class="text-2xl font-bold text-[#E6EB9C] mb-6">
            Persentase
        </h2>

        <div class="relative h-[430px] flex items-center justify-center">
            <canvas id="blankspotPieChart"></canvas>
        </div>

    </div>

</div>

        <div class="bg-[#F3F3E8] rounded-3xl shadow-2xl mt-10 p-8">
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
                            <th class="px-4 py-3 font-bold">Kabupaten/Kota</th>
                            <th class="px-4 py-3 font-bold">Nama Kecamatan</th>
                            <th class="px-4 py-3 font-bold">Nama Desa</th>
                            <th class="px-4 py-3 font-bold">Longitude</th>
                            <th class="px-4 py-3 font-bold">Latitude</th>
                            <th class="px-3 py-3 font-bold">Prioritas</th>
                            <th class="px-3 py-3 font-bold">Status Jaringan</th>
                            <th class="px-3 py-3 font-bold">Kondisi<br>Geografis</th>
                            <th class="px-3 py-3 font-bold">Jumlah<br>Penduduk</th>
                            <th class="px-3 py-3 font-bold">Jarak ke<br>Ibu Kota</th>
                            <th class="px-4 py-3 text-center font-bold">Tahun</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @if(isset($blankSpots) && count($blankSpots) > 0)
                            @foreach($blankSpots as $i => $spot)
                            <tr class="border-b border-gray-200 hover:bg-[#F3F3E8]/50 transition">
                                <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">{{ $spot->kabupaten->nama_kabupaten ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $spot->kecamatan->nama_kecamatan ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $spot->desa->nama_desa ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $spot->longitude }}</td>
                                <td class="px-4 py-3">{{ $spot->latitude }}</td>
                                <td class="px-4 py-3 font-bold text-amber-800">{{ $spot->prioritas ? 'P' . $spot->prioritas : '-' }}</td>
                                <td class="px-4 py-3">{{ $spot->status_jaringan ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $spot->kondisi_geografis ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $spot->jumlah_penduduk ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $spot->jarak_ibukota ? $spot->jarak_ibukota . ' Km' : '-' }}</td>
                                <td class="px-4 py-3 text-center">{{ $spot->tahun }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12" class="text-center py-8 text-gray-400">Belum ada data blank spot.</td>
                            </tr>
                        @endif
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

    <!-- TAB GEOSPASIAL -->
    <div id="content-geo" class="tab-content hidden mt-10">
        <div class="flex flex-col lg:flex-row-reverse gap-6">
            <div class="w-full lg:w-1/4 bg-[#F3F3E8] rounded-3xl p-6 shadow-2xl border border-[#234B26]/10 h-fit space-y-5">
                <div>
                    <label for="geoRegion" class="block text-[#234B26] font-bold text-lg mb-2">Spasial (Wilayah)</label>
                    <div class="relative">
                        <select id="geoRegion" class="w-full bg-[#234B26] text-white px-4 py-3 rounded-xl outline-none text-base font-semibold cursor-pointer appearance-none pr-10">
                            <option value="all">Semua Kabupaten/Kota</option>
                            <option value="1">Medan</option>
<option value="2">Deli Serdang</option>
<option value="3">Binjai</option>
<option value="4">Langkat</option>
<option value="5">Simalungun</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-white text-xs">▼</div>
                    </div>
                </div>
                <div>
                    <label for="geoYear" class="block text-[#234B26] font-bold text-lg mb-2">Tahun</label>
                    <div class="relative">
                        <select id="geoYear" class="w-full bg-[#234B26] text-white px-4 py-3 rounded-xl outline-none text-base font-semibold cursor-pointer appearance-none pr-10">
                            <option value="">Pilih Tahun</option>
                            <option>2021</option>
<option>2022</option>
<option>2023</option>
<option>2024</option>
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

    document.querySelectorAll("#tableBody tr").forEach(row => {
        row.dataset.filtered = "show";
    });

    renderTable();

    initDashboardCharts();

});
let barChart = null;
let pieChart = null;
const chartLabels = [
    "Medan",
    "Binjai",
    "Tebing Tinggi",
    "Pematangsiantar",
    "Tanjungbalai",
    "Sibolga",
    "Padangsidimpuan",
    "Gunungsitoli",
    "Deli Serdang",
    "Langkat",
    "Karo",
    "Simalungun",
    "Asahan",
    "Labuhanbatu",
    "Labuhanbatu Utara",
    "Labuhanbatu Selatan",
    "Batubara",
    "Serdang Bedagai",
    "Samosir",
    "Toba",
    "Tapanuli Utara",
    "Tapanuli Tengah",
    "Tapanuli Selatan",
    "Mandailing Natal",
    "Padang Lawas",
    "Padang Lawas Utara",
    "Nias",
    "Nias Selatan",
    "Nias Utara",
    "Nias Barat",
    "Pakpak Bharat",
    "Humbang Hasundutan",
    "Dairi"
];

const chartValues = [
    45, // Medan
    18, // Binjai
    12, // Tebing Tinggi
    21, // Pematangsiantar
    16, // Tanjungbalai
    10, // Sibolga
    24, // Padangsidimpuan
    13, // Gunungsitoli
    39, // Deli Serdang
    31, // Langkat
    22, // Karo
    28, // Simalungun
    25, // Asahan
    20, // Labuhanbatu
    15, // Labuhanbatu Utara
    14, // Labuhanbatu Selatan
    17, // Batubara
    26, // Serdang Bedagai
    11, // Samosir
    19, // Toba
    23, // Tapanuli Utara
    27, // Tapanuli Tengah
    18, // Tapanuli Selatan
    30, // Mandailing Natal
    12, // Padang Lawas
    14, // Padang Lawas Utara
    9,  // Nias
    16, // Nias Selatan
    8,  // Nias Utara
    7,  // Nias Barat
    6,  // Pakpak Bharat
    13, // Humbang Hasundutan
    15  // Dairi
];
function initDashboardCharts() {

    // =========================
    // HORIZONTAL BAR CHART
    // =========================

    const barCanvas = document.getElementById('blankspotBarChart');

    if (barCanvas) {

        // Tambahkan di sini
        barCanvas.parentElement.style.height = (chartLabels.length * 24) + "px";

        if (barChart) {
            barChart.destroy();
        }

        barChart = new Chart(barCanvas, {
            type: 'bar',

            data: {
                labels: chartLabels,

                datasets: [{
                    label: 'Jumlah Blankspot',
                    data: chartValues,
                    backgroundColor: '#234B26',
                    borderRadius: 4,
                    barThickness: 12
                }]
            },

            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        padding: 12,
                        cornerRadius: 10
                    }
                },

                scales: {

                    x: {
                        beginAtZero: true,

                        grid: {
                            color: 'rgba(35, 75, 38, 0.1)'
                        },

                        ticks: {
                            color: '#234B26',
                            font: {
                                weight: 'bold'
                            }
                        }
                    },

                    y: {

                        grid: {
                            display: false
                        },

                        ticks: {
                            color: '#234B26',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    }


    // =========================
    // PIE / DOUGHNUT CHART
    // =========================

    // =========================
// PIE / DOUGHNUT CHART
// =========================

const pieCanvas = document.getElementById('blankspotPieChart');

if (pieCanvas) {

    if (pieChart) {
        pieChart.destroy();
    }

    pieChart = new Chart(pieCanvas, {

        type: 'pie',

data: {
    labels: [
        "Zero Blankspot",
        "Sinyal Sangat Lemah",
        "Sinyal Lemah",
        "2G",
        "3G",
        "4G Tidak Stabil"
    ],

    datasets: [{
        data: [
            25,
            18,
            20,
            15,
            12,
            10
        ],

        backgroundColor: [
    '#E6EB9C',
    '#D8E58A',
    '#C5DB75',
    '#AED05F',
    '#95C04F',
    '#79AD45'
],

        borderColor: '#234B26',
        borderWidth: 2
    }]
},       options: {

            responsive: true,
            maintainAspectRatio: false,

            plugins: {

                legend: {
    position: 'bottom',
    align: 'start', // rata kiri

    labels: {
        color: '#E6EB9C',
        boxWidth: 18,
        boxHeight: 18,
        padding: 20,
        font: {
            size: 15,
            weight: 'bold'
        }
    }
},

                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ": " + context.raw + "%";
                        }
                    }
                }
            }
        }
    });
}
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
const spotsData = [
    {
        latitude: 3.5952,
        longitude: 98.6722,
        tahun: 2024,
        kabupaten_id: 1,
        kecamatan: {
            nama_kecamatan: "Medan Kota"
        },
        desa: {
            nama_desa: "Teladan"
        }
    },
    {
        latitude: 3.5600,
        longitude: 98.8800,
        tahun: 2024,
        kabupaten_id: 2,
        kecamatan: {
            nama_kecamatan: "Lubuk Pakam"
        },
        desa: {
            nama_desa: "Pagar Jati"
        }
    },
    {
        latitude: 3.7300,
        longitude: 98.4300,
        tahun: 2023,
        kabupaten_id: 3,
        kecamatan: {
            nama_kecamatan: "Stabat"
        },
        desa: {
            nama_desa: "Kwala Begumit"
        }
    },
    {
        latitude: 2.954,
        longitude: 98.981,
        tahun: 2022,
        kabupaten_id: 4,
        kecamatan: {
            nama_kecamatan: "Raya"
        },
        desa: {
            nama_desa: "Sondi Raya"
        }
    }
];
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

    if (!map) {
        alert("Peta belum siap");
        return;
    }

    const region = document.getElementById("geoRegion").value;
    const year = document.getElementById("geoYear").value;

    let filtered = blankspotLocations.filter(loc => {
        return (
            (region === "all" || loc.kab == region) &&
            (year === "" || loc.year == year)
        );
    });

    renderMarkers(filtered);

    if (filtered.length > 0) {
        const bounds = filtered.map(l => [l.lat, l.lng]);
        map.fitBounds(bounds, { padding: [40, 40] });
    } else {
        alert("Tidak ada data.");
    }
}
</script>

@endsection