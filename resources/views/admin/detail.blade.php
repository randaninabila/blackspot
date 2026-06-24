@extends('app')

@section('content')

<section class="max-w-7xl mx-auto py-10 px-8 relative">

    <div class="flex items-center gap-5 mb-6">

    <!-- BACK BUTTON -->
    <button onclick="history.back()"
        class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#234B26] text-white hover:bg-[#1a381c] transition shadow-md">

        <!-- icon panah kiri -->
        <svg xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="2.5"
            stroke="currentColor"
            class="w-5 h-5">

            <path stroke-linecap="round"
                stroke-linejoin="round"
                d="M15 19l-7-7 7-7" />
        </svg>

    </button>

    <!-- TITLE -->
    <h2 class="text-3xl font-bold text-[#234B26]">
        Kota Medan
    </h2>

</div>

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
               
<div class="flex items-center gap-3 flex-1 md:flex-none justify-end">
                <div class="relative w-full md:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-[#234B26]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>    
            <input id="searchInput"
                       onkeyup="searchTable()"
                       type="text"
                       placeholder="Cari kecamatan / desa..."
                       class="w-full md:w-80 border-2 border-[#234B26] rounded-xl pl-10 py-2 outline-none focus:ring-2 focus:ring-[#234B26]/20">
                </div>
                <button onclick="openModal()" 
                        class="bg-[#234B26] text-white font-semibold px-5 py-2.5 rounded-xl hover:bg-[#1a381c] transition-colors shadow-sm flex items-center whitespace-nowrap">
                    + Tambah
                </button>
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
</section>






<div id="blankspotModal"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">

    <div class="bg-[#234B26] w-full max-w-md p-6 rounded-3xl shadow-2xl border border-white/10 mx-4 transform scale-95 transition-transform duration-300" id="modalContent">

        <div class="text-center mb-4">
            <h3 class="text-xl font-bold text-[#E6EB9C]">Masukkan Data</h3>
            <p class="text-xl italic font-bold text-[#E6EB9C]">Blankspot</p>
        </div>

        <form action="#" method="POST" class="space-y-3">
            @csrf

            <!-- KECAMATAN -->
           <!-- KECAMATAN -->
<div>
    <label class="block text-white font-semibold mb-1.5 text-sm">Kecamatan</label>

    <select
        class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
        <option value="">Pilih Kecamatan</option>
        <option>Medan Baru</option>
        <option>Medan Petisah</option>
        <option>Medan Kota</option>
        <option>Medan Sunggal</option>
    </select>
</div>

            <!-- DESA -->
            <div>
                <label class="block text-white font-semibold mb-1.5 text-sm">Nama Desa</label>
                <input type="text" placeholder="Desa Laudendang"
                       class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
            </div>

            <!-- LONGITUDE -->
            <div>
                <label class="block text-white font-semibold mb-1.5 text-sm">Longitude</label>
                <input type="text" placeholder="98.67559790"
                       class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
            </div>

            <!-- LATITUDE -->
            <div>
                <label class="block text-white font-semibold mb-1.5 text-sm">Latitude</label>
                <input type="text" placeholder="3.58524200"
                       class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
            </div>

            <!-- TANGGAL -->
            <div>
                <label class="block text-white font-semibold mb-1.5 text-sm">Tanggal</label>
                <input type="date"
                       class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3 pt-3">
                <button type="button" onclick="closeModal()"
                        class="bg-white text-red-700 font-bold px-4 py-2 rounded-lg hover:bg-gray-200 text-sm">
                    Cancel
                </button>

                <button type="submit"
                        class="bg-white text-[#234B26] font-bold px-4 py-2 rounded-lg hover:bg-gray-200 text-sm">
                    Tambahkan
                </button>
            </div>

        </form>

    </div>
</div>

<script>
let currentPage = 1;
let perPage = 5;

/* =======================================
   MODAL INTERACTIVITY LOGIC (POP-UP)
======================================= */
function openModal() {
    const modal = document.getElementById('blankspotModal');
    const content = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    // Efek transisi melayang halus
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('blankspotModal');
    const content = document.getElementById('modalContent');
    
    modal.classList.add('opacity-0');
    content.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Menutup modal jika user mengklik area background hitam di luar form box
window.onclick = function(event) {
    const modal = document.getElementById('blankspotModal');
    if (event.target == modal) {
        closeModal();
    }
}

/* =======================================
   EXISTING CORE LOGIC (PAGINATION & SEARCH)
======================================= */
function getRows() {
    return [...document.querySelectorAll("#tableBody tr")]
        .filter(row => row.dataset.filtered !== "hidden");
}

function renderTable() {
    const rows = getRows();
    const totalPages = Math.ceil(rows.length / perPage) || 1;

    if (currentPage > totalPages) currentPage = totalPages;

    document.querySelectorAll("#tableBody tr").forEach(row => {
        row.classList.add("hidden");
    });

    const start = (currentPage - 1) * perPage;
    const end = start + perPage;

    rows.forEach((row, i) => {
        if (i >= start && i < end) {
            row.classList.remove("hidden");
        }
    });

    renderPagination(rows.length);

    document.getElementById("showTotal").textContent = rows.length;
    document.getElementById("showStart").textContent = rows.length ? start + 1 : 0;
    document.getElementById("showEnd").textContent = Math.min(end, rows.length);
}

function renderPagination(total) {
    const totalPages = Math.ceil(total / perPage) || 1;
    const container = document.getElementById("paginationNumbers");

    container.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement("button");
        btn.innerText = i;

        btn.className = i === currentPage
            ? "px-3 py-1 bg-[#234B26] text-white rounded-lg font-semibold"
            : "px-3 py-1 border border-[#234B26] text-[#234B26] hover:bg-[#234B26]/10 rounded-lg font-semibold transition-colors";

        btn.onclick = () => {
            currentPage = i;
            renderTable();
        };

        container.appendChild(btn);
    }

    document.getElementById("prevBtn").disabled = currentPage === 1;
    document.getElementById("nextBtn").disabled = currentPage === totalPages;
}

function nextPage() {
    const totalPages = Math.ceil(getRows().length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderTable();
    }
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        renderTable();
    }
}

function searchTable() {
    const keyword = document.getElementById("searchInput").value.toLowerCase();

    document.querySelectorAll("#tableBody tr").forEach(row => {
        const text = row.textContent.toLowerCase();
        row.dataset.filtered = text.includes(keyword) ? "show" : "hidden";
    });

    currentPage = 1;
    renderTable();
}

function changeEntries() {
    perPage = parseInt(document.getElementById("entries").value);
    currentPage = 1;
    renderTable();
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("#tableBody tr").forEach(row => {
        row.dataset.filtered = "show";
    });
    renderTable();
});
</script>

@endsection