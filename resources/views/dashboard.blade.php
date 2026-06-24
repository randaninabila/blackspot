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
            <h3 class="text-5xl font-bold my-2">39</h3>
            <p class="font-semibold">Data Tahun 2023</p>
        </div>

        <div class="bg-[#234B26] text-[#E6EB9C] rounded-2xl p-6">
            <p class="font-semibold">Nilai Rata-rata Tiap Tahun</p>
            <h3 class="text-5xl font-bold my-2">39</h3>
            <p class="font-semibold">4 Tahun Terakhir</p>
        </div>

        <div class="bg-[#234B26] text-[#E6EB9C] rounded-2xl p-6">
            <p class="font-semibold">Nilai Tertinggi</p>
            <h3 class="text-5xl font-bold my-2">39</h3>
            <p class="font-semibold">Tahun 2020</p>
        </div>

        <div class="bg-[#234B26] text-[#E6EB9C] rounded-2xl p-6">
            <p class="font-semibold">Nilai Terendah</p>
            <h3 class="text-5xl font-bold my-2">39</h3>
            <p class="font-semibold">Tahun 2021</p>
        </div>

    </div>

    <!-- TOP ACTION -->
    <div class="flex justify-between items-center mt-10">

        <!-- Tabs -->
        <div class="flex border border-[#234B26] rounded-2xl overflow-hidden">

            <button onclick="switchTab('table')" id="btn-table"
                class="tab-btn active-tab px-14 py-3 font-semibold border-r border-[#234B26]">
                Tabel
            </button>

            <button onclick="switchTab('grafik')" id="btn-grafik"
                class="tab-btn inactive-tab px-14 py-3 font-semibold border-r border-[#234B26]">
                Grafik
            </button>

            <button onclick="switchTab('geo')" id="btn-geo"
                class="tab-btn inactive-tab px-14 py-3 font-semibold border-r border-[#234B26]">
                Geopasial
            </button>

        </div>

        <!-- Download -->
        <button
  class="bg-[#234B26] text-white px-6 py-3 rounded-xl font-medium hover:opacity-90 flex items-center gap-2">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    class="w-5 h-5"
    fill="none"
    viewBox="0 0 24 24"
    stroke="currentColor">
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      stroke-width="2"
      d="M12 4v10m0 0l-4-4m4 4l4-4m-9 8h10" />
  </svg>
  <span>Download</span>
</button>

    </div>



    <!-- CONTENT TABLE -->
    <div id="content-table" class="tab-content mt-10">

        <div class="bg-[#F3F3E8] rounded-3xl shadow-2xl p-8">

            <!-- Filter Top -->
            <div class="flex justify-between items-center">

                <!-- Dropdown -->
                <div class="flex items-center gap-3">

                    <span class="text-[#234B26] font-bold text-2xl">
                        Tampilkan
                    </span>

                    <div class="relative w-fit">
    <select
        id="entries"
        onchange="changeEntries()"
        class="appearance-none bg-[#234B26] text-white pl-3 pr-7 py-1.5  rounded-lg outline-none text-lg font-semibold cursor-pointer">

        <option selected>10</option>
        <option>20</option>
        <option>30</option>
        <option>40</option>
        <option>50</option>

    </select>

    <!-- custom arrow -->
    <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-white text-sm">
        ▼
    </div>
</div>

                    <span class="text-[#234B26] font-bold text-2xl">
                        Data
                    </span>

                </div>


                <!-- Search -->
               
<div class="relative w-full md:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-[#234B26]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>    
                    <input
                        id="searchInput"
                        onkeyup="searchTable()"
                        type="text"
                        placeholder="Cari nama kota/kabupaten..."
                        class="w-80 border-2 border-[#234B26] rounded-2xl px-9 py-3 outline-none">

                </div>

            </div>



            <!-- TABLE -->
            <div class="mt-10">

                <table class="w-full text-[#234B26]" id="dataTable">

                    <thead class="border-b border-[#234B26]">

                        <tr class="text-left">

                            <th class="pl-5 pb-4">No</th>
                            <th class="pb-4">Nama Kab/Kota</th>
                            <th class="pb-4">Nama Kecamatan</th>
                            <th class="pb-4">Nama Desa</th>
                            <th class="pb-4">Longitude</th>
                            <th class="pb-4">Latitude</th>
                            <th class="pb-4">Tahun</th>

                        </tr>

                    </thead>

                    <tbody id="tableBody">

    <tr class="border-b">
        <td class="py-3 pl-5">1</td>
        <td>Kota Medan</td>
        <td>Kota Medan</td>
        <td>Medan Baru</td>
        <td>98.6722</td>
        <td>3.5952</td>
        <td>2025</td>
    </tr>

    <tr class="border-b">
        <td class="py-3 pl-5">2</td>
        <td>Kota Medan</td>
        <td>Binjai</td>
        <td>Binjai Timur</td>
        <td>98.5020</td>
        <td>3.6200</td>
        <td>2024</td>
    </tr>

    <tr class="border-b">
        <td class="py-3 pl-5">3</td>
        <td>Kota Binjai</td>
        <td>Deli Serdang</td>
        <td>Tanjung Morawa</td>
        <td>98.7900</td>
        <td>3.5000</td>
        <td>2023</td>
    </tr>

    <tr class="border-b">
        <td class="py-3 pl-5">4</td>
        <td>Kabupaten Nias</td>
        <td>Pematangsiantar</td>
        <td>Siantar Barat</td>
        <td>99.0687</td>
        <td>2.9600</td>
        <td>2022</td>
    </tr>

    <tr class="border-b">
        <td class="py-3 pl-5">5</td>
        <td>Kabupaten Hitz</td>
        <td>Tebing Tinggi</td>
        <td>Rambutan</td>
        <td>99.1623</td>
        <td>3.3270</td>
        <td>2021</td>
    </tr>

    <tr class="border-b">
        <td class="py-3 pl-5">6</td>
        <td>Kota Medan</td>
        <td>Langkat</td>
        <td>Stabat</td>
        <td>98.4501</td>
        <td>3.7305</td>
        <td>2020</td>
    </tr>

    <tr class="border-b">
        <td class="py-3 pl-5">7</td>
        <td>Kabupaten Deli Serdang</td>
        <td>Lubuk Pakam</td>
        <td>Lubuk Pakam</td>
        <td>98.8702</td>
        <td>3.5580</td>
        <td>2019</td>
    </tr>

    <tr class="border-b">
        <td class="py-3 pl-5">8</td>
        <td>Kota Binjai</td>
        <td>Binjai Selatan</td>
        <td>Binjai Selatan</td>
        <td>98.4803</td>
        <td>3.6102</td>
        <td>2018</td>
    </tr>

    <tr class="border-b">
        <td class="py-3 pl-5">9</td>
        <td>Kabupaten Karo</td>
        <td>Berastagi</td>
        <td>Berastagi</td>
        <td>98.5045</td>
        <td>3.1947</td>
        <td>2017</td>
    </tr>

    <tr class="border-b">
        <td class="py-3 pl-5">10</td>
        <td>Kabupaten Simalungun</td>
        <td>Raya</td>
        <td>Raya</td>
        <td>99.0612</td>
        <td>2.9541</td>
        <td>2016</td>
    </tr>

    <tr class="border-b">
        <td class="py-3 pl-5">11</td>
        <td>Kota Pematangsiantar</td>
        <td>Siantar Utara</td>
        <td>Siantar Utara</td>
        <td>99.0789</td>
        <td>2.9655</td>
        <td>2015</td>
    </tr>

    <tr class="border-b">
        <td class="py-3 pl-5">12</td>
        <td>Kabupaten Labuhanbatu</td>
        <td>Rantauprapat</td>
        <td>Rantau Utara</td>
        <td>100.0523</td>
        <td>2.1004</td>
        <td>2014</td>
    </tr>

</tbody>

                </table>

            </div>

            <!-- Pagination -->
<div class="flex flex-col md:flex-row items-center justify-between gap-4 mt-10">

    <!-- Info -->
    <p class="text-lg font-medium text-[#234B26]">
        Menampilkan
        <span id="showStart">1</span> -
        <span id="showEnd">10</span>
        dari
        <span id="showTotal">0</span>
        data
    </p>

    <!-- Navigation -->
    <div class="flex items-center gap-2">

        <button
            id="prevBtn"
            onclick="prevPage()"
            class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#234B26] text-[#E6EB9C] hover:opacity-90 disabled:opacity-40 disabled:cursor-not-allowed">
            ←
        </button>

        <div
            id="paginationNumbers"
            class="flex items-center gap-2">
        </div>

        <button
            id="nextBtn"
            onclick="nextPage()"
            class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#234B26] text-[#E6EB9C] hover:opacity-90 disabled:opacity-40 disabled:cursor-not-allowed">
            →
        </button>

    </div>

</div>

        </div>

    </div>


    <!-- TAB LAIN -->
    <div id="content-grafik" class="tab-content hidden mt-10">
    <div class="flex flex-col lg:flex-row gap-6">
        
        <div class="w-full lg:w-1/4 bg-[#F3F3E8] rounded-3xl p-6 shadow-2xl border border-[#234B26]/10 h-fit">
            <label for="chartType" class="block text-[#234B26] font-bold text-xl mb-3.5">
                Jenis Grafik
            </label>
            <div class="relative">
                <select id="chartType" onchange="updateChartType()"
                    class="w-full bg-[#234B26] text-white px-5 py-3 rounded-2xl outline-none text-base font-semibold cursor-pointer appearance-none pr-10 shadow-lg">
                    <option value="bar">Bar Chart</option>
                    <option value="line">Line Chart</option>
                </select>
                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-white font-bold">
                    ▼
                </div>
            </div>
        </div>

        <div class="w-full lg:w-3/4 bg-[#F3F3E8] rounded-3xl p-6 md:p-8 shadow-2xl border border-[#234B26]/10">
            <h3 class="text-[#234B26] font-bold text-2xl text-center mb-6">
                Data Blankspot Sumatra Utara
            </h3>
            <div class="relative w-full h-[450px]">
                <canvas id="blankspotChart"></canvas>
            </div>
        </div>

    </div>
</div>

    <div id="content-geo" class="tab-content hidden mt-10">
    <div class="flex flex-col lg:flex-row-reverse gap-6">
        
        <div class="w-full lg:w-1/4 bg-[#F3F3E8] rounded-3xl p-6 shadow-2xl border border-[#234B26]/10 h-fit space-y-5">
            
            <div>
                <label for="geoRegion" class="block text-[#234B26] font-bold text-lg mb-2">
                    Spasial (Wilayah)
                </label>
                <div class="relative">
                    <select id="geoRegion"
                        class="w-full bg-[#234B26] text-white px-4 py-3 rounded-xl outline-none text-base font-semibold cursor-pointer appearance-none pr-10">
                        <option value="all">Semua Kabupaten/Kota</option>
                        <option value="medan">Kota Medan</option>
                        <option value="binjai">Binjai</option>
                        <option value="deliserdang">Deli Serdang</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-white text-xs">
                        ▼
                    </div>
                </div>
            </div>

            <div>
                <label for="geoYear" class="block text-[#234B26] font-bold text-lg mb-2">
                    Tahun
                </label>
                <div class="relative">
                    <select id="geoYear"
                        class="w-full bg-[#234B26] text-white px-4 py-3 rounded-xl outline-none text-base font-semibold cursor-pointer appearance-none pr-10">
                        <option value="">Pilih Tahun</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-white text-xs">
                        ▼
                    </div>
                </div>
            </div>

            <button onclick="filterGeospatial()"
                class="w-full bg-[#234B26] text-[#E6EB9C] hover:bg-[#1b3a1d] font-bold py-3 px-4 rounded-xl transition duration-200 shadow-md tracking-wider text-sm mt-2">
                Pratinjau
            </button>

        </div>

        <div class="w-full lg:w-3/4 bg-[#F3F3E8] rounded-3xl p-4 shadow-2xl border border-[#234B26]/10">
            <div id="map" class="w-full h-[500px] rounded-2xl z-10 shadow-inner"></div>
        </div>

    </div>
</div>

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

.active-page{
    background:#234B26;
    color:white;
    padding:4px 10px;
    border-radius:6px;
}

.page-number{
    padding:4px 10px;
    border-radius:6px;
}

.page-btn{
    background:#234B26;
    color:white;
    width:28px;
    height:28px;
    border-radius:999px;
}

</style>


<script>

/* TAB SWITCH */
function switchTab(tab){

    document.querySelectorAll('.tab-content').forEach(el=>{
        el.classList.add('hidden');
    });

    document.querySelectorAll('.tab-btn').forEach(el=>{
        el.classList.remove('active-tab');
        el.classList.add('inactive-tab');
    });

    document.getElementById('content-' + tab).classList.remove('hidden');

    let btn=document.getElementById('btn-' + tab);
    btn.classList.remove('inactive-tab');
    btn.classList.add('active-tab');
}


/* SEARCH */
function searchTable(){

    let input=document.getElementById("searchInput").value.toLowerCase();

    let rows=document.querySelectorAll("#tableBody tr");

    rows.forEach(row=>{

        let text=row.textContent.toLowerCase();

        if(text.includes(input)){
            row.style.display="";
        }else{
            row.style.display="none";
        }

    });

}


/* DROPDOWN */
function changeEntries(){

    let selected=document.getElementById("entries").value;

    alert("Menampilkan " + selected + " data");

    // nanti bisa dihubungkan ke backend pagination Laravel

}


/* =========================
   PAGINATION
========================= */

let currentPage = 1;
let perPage = parseInt(
    document.getElementById("entries").value
);

/* ambil row yang lolos filter */
function getRows() {

    return [...document.querySelectorAll("#tableBody tr")]
        .filter(row => row.dataset.filtered !== "hidden");
}

/* render table */
function renderTable() {

    const rows = getRows();

    const totalPages =
        Math.ceil(rows.length / perPage) || 1;

    if(currentPage > totalPages){
        currentPage = totalPages;
    }

    document
        .querySelectorAll("#tableBody tr")
        .forEach(row => {
            row.classList.add("hidden");
        });

    const start =
        (currentPage - 1) * perPage;

    const end =
        start + perPage;

    rows.forEach((row,index)=>{

        if(index >= start && index < end){

            row.classList.remove("hidden");

        }

    });

    renderPagination(rows.length);

    document.getElementById("showTotal").textContent =
        rows.length;

    document.getElementById("showStart").textContent =
        rows.length === 0
            ? 0
            : start + 1;

    document.getElementById("showEnd").textContent =
        Math.min(end, rows.length);
}

/* render nomor halaman */
function renderPagination(totalData){

    const totalPages =
        Math.ceil(totalData / perPage) || 1;

    const container =
        document.getElementById(
            "paginationNumbers"
        );

    container.innerHTML = "";

    for(let i = 1; i <= totalPages; i++){

        const btn =
            document.createElement("button");

        btn.innerText = i;

        btn.className =
            i === currentPage
            ? `
                w-10 h-10
                rounded-xl
                bg-[#E6EB9C]
                text-[#234B26]
                border
                border-[#234B26]
                font-bold
              `
            : `
                w-10 h-10
                rounded-xl
                border
                border-[#234B26]
                text-[#234B26]
                hover:bg-[#E6EB9C]
                transition
              `;

        btn.onclick = () => {

            currentPage = i;

            renderTable();
        };

        container.appendChild(btn);
    }

    document.getElementById("prevBtn").disabled =
        currentPage === 1;

    document.getElementById("nextBtn").disabled =
        currentPage === totalPages;
}

/* next */
function nextPage(){

    const totalPages =
        Math.ceil(
            getRows().length / perPage
        );

    if(currentPage < totalPages){

        currentPage++;

        renderTable();
    }
}

/* prev */
function prevPage(){

    if(currentPage > 1){

        currentPage--;

        renderTable();
    }
}

/* =========================
   SEARCH
========================= */

function searchTable(){

    const keyword =
        document.getElementById(
            "searchInput"
        )
        .value
        .toLowerCase();

    document
        .querySelectorAll("#tableBody tr")
        .forEach(row => {

            const text =
                row.textContent.toLowerCase();

            if(text.includes(keyword)){

                row.dataset.filtered = "show";

            }else{

                row.dataset.filtered = "hidden";
            }

        });

    currentPage = 1;

    renderTable();
}

/* =========================
   ENTRIES
========================= */

function changeEntries(){

    perPage = parseInt(
        document.getElementById(
            "entries"
        ).value
    );

    currentPage = 1;

    renderTable();
}

/* =========================
   INIT
========================= */

document.addEventListener(
    "DOMContentLoaded",
    () => {

        document
            .querySelectorAll("#tableBody tr")
            .forEach(row => {

                row.dataset.filtered =
                    "show";
            });

        renderTable();
    }
);

/* =========================
   GRAFIK (CHART.JS)
========================= */
let myChart = null;

// Mock Data representasi visual sesuai gambar Grafikk.png
const chartData = {
    labels: ['2022', '2023', '2024', '2025', '2026'],
    datasets: [
        {
            label: 'Kota Medan',
            data: [11, 17, 27, 18, 14],
            backgroundColor: '#86EFAC', // Hijau muda soft
            borderColor: '#86EFAC',
            borderWidth: 1,
            borderRadius: 8
        },
        {
            label: 'Binjai',
            data: [12, 8, 17, 14, 17],
            backgroundColor: '#FEF08A', // Kuning soft
            borderColor: '#FEF08A',
            borderWidth: 1,
            borderRadius: 8
        },
        {
            label: 'Deli Serdang',
            data: [6, 17, 16, 14, 10],
            backgroundColor: '#93C5FD', // Biru muda soft
            borderColor: '#93C5FD',
            borderWidth: 1,
            borderRadius: 8
        },
        {
            label: 'Pematangsiantar',
            data: [12, 16, 8, 13, 13],
            backgroundColor: '#FDBA74', // Oranye soft
            borderColor: '#FDBA74',
            borderWidth: 1,
            borderRadius: 8
        }
    ]
};

function initChart(type = 'bar') {
    const ctx = document.getElementById('blankspotChart').getContext('2d');
    
    // Jika chart sudah ada, hancurkan dulu sebelum membuat yang baru
    if (myChart) {
        myChart.destroy();
    }

    myChart = new Chart(ctx, {
        type: type,
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: '#234B26',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        boxWidth: 20,
                        padding: 20
                    }
                },
                tooltip: {
                    padding: 12,
                    cornerRadius: 12
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false // Menghilangkan garis vertikal background agar bersih
                    },
                    ticks: {
                        color: '#234B26',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                },
                y: {
                    min: 0,
                    max: 30,
                    ticks: {
                        stepSize: 5,
                        color: '#234B26',
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(35, 75, 38, 0.1)' // Garis horizontal tipis transparan
                    }
                }
            }
        }
    });
}

// Fungsi trigger saat user mengubah tipe chart (Bar / Line)
function updateChartType() {
    const selectedType = document.getElementById('chartType').value;
    initChart(selectedType);
}

// Modifikasi fungsi switchTab bawaan Anda agar otomatis merender grafik saat tab dibuka
const originalSwitchTab = switchTab; 
switchTab = function(tab) {
    originalSwitchTab(tab); // Jalankan fungsi tab bawaan
    if (tab === 'grafik') {
        // Berikan delay microsecond agar canvas selesai dimuat di DOM sebelum digambar
        setTimeout(() => {
            initChart(document.getElementById('chartType').value);
        }, 50);
    }
}

/* =========================
   GEOSPASIAL (LEAFLET.JS)
========================= */
let map = null;
let markersLayer = L.layerGroup(); // Tempat menampung pin koordinat agar mudah dihapus/filter

// Mock Data koordinat dari tabel Anda untuk dipasang sebagai pin
const blankspotLocations = [
    { name: "Medan Baru (Kota Medan)", lat: 3.5952, lng: 98.6722, year: "2025" },
    { name: "Binjai Timur (Binjai)", lat: 3.6200, lng: 98.5020, year: "2024" },
    { name: "Tanjung Morawa (Deli Serdang)", lat: 3.5000, lng: 98.7900, year: "2023" }
];

function initMap() {
    // Cegah inisialisasi ulang jika map sudah terbuat sebelumnya
    if (map !== null) {
        map.invalidateSize(); // Refresh ukuran peta jika tampilannya sempat terpotong
        return;
    }

    // Koordinat pusat Sumatra Utara [Lat, Lng] & level zoom 8
    map = L.map('map').setView([3.5952, 98.6722], 8);

    // Menggunakan tile layer gratis dari OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    markersLayer.addTo(map);
    renderMarkers(); // Gambar marker pertama kali
}

function renderMarkers() {
    markersLayer.clearLayers(); // Bersihkan peta sebelum plotting baru

    blankspotLocations.forEach(loc => {
        // Buat pin koordinat beserta popup infonya saat diklik
        const marker = L.marker([loc.lat, loc.lng])
            .bindPopup(`<b>${loc.name}</b><br>Tahun: ${loc.year}`);
        markersLayer.addLayer(marker);
    });
}

// Fungsi dummy ketika tombol Pratinjau diklik
function filterGeospatial() {
    const region = document.getElementById("geoRegion").value;
    const year = document.getElementById("geoYear").value;
    
    alert(`Memfilter Peta untuk Wilayah: ${region} | Tahun: ${year || 'Semua'}`);
    // Di sini Anda bisa menambahkan logika penyaringan koordinat berdasarkan input user
}

// Modifikasi fungsi switchTab bawaan agar peta dimuat saat tab Geospasial aktif
const baseSwitchTab = switchTab; 
switchTab = function(tab) {
    baseSwitchTab(tab); 
    if (tab === 'geo') {
        // Beri jeda sedikit agar container selesai dirender oleh browser sebelum memuat peta
        setTimeout(() => {
            initMap();
        }, 100);
    }
}

</script>

@endsection
