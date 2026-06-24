@extends('app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-bold text-[#234B26] mb-8 tracking-tight font-sans">
        Daftar Kabupaten/Kota Provinsi Sumatera Utara
    </h1>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        
        <div class="flex border border-[#234B26] rounded-2xl overflow-hidden shadow-sm bg-white self-start">
            <button onclick="filterWilayah('semua')" id="btn-semua"
                class="filter-btn active-tab px-10 py-3 font-semibold border-r border-[#234B26] transition-colors text-sm">
                Semua
            </button>
            <button onclick="filterWilayah('kota')" id="btn-kota"
                class="filter-btn inactive-tab px-10 py-3 font-semibold border-r border-[#234B26] transition-colors text-sm">
                Kota
            </button>
            <button onclick="filterWilayah('kabupaten')" id="btn-kabupaten"
                class="filter-btn inactive-tab px-10 py-3 font-semibold transition-colors text-sm">
                Kabupaten
            </button>
        </div>

        <div class="relative w-full md:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-[#234B26]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input 
                type="text" 
                id="searchInput"
                onkeyup="searchCards()" {{-- Memanggil fungsi pencarian --}}
                placeholder="Cari Kab/Kota..." 
                class="w-full pl-10 pr-4 py-3 bg-white border border-[#234B26]/30 rounded-2xl focus:outline-none focus:ring-2 focus:ring-[#234B26] focus:border-transparent text-sm placeholder-gray-400 shadow-sm"
            >
        </div>
    </div>

    <div id="cardContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-10">
        
        <a href="/adetail" data-type="kota" data-nama="kota binjai" class="wilayah-card group block bg-[#234B26] text-white rounded-2xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:bg-[#1a381c]">
            <div class="flex flex-col h-full justify-between">
                <div>
                    <h3 class="text-lg font-semibold tracking-wide min-h-[3.5rem]">Kota Binjai</h3>
                    <p class="text-5xl font-bold mt-2 text-[#E6EB9C] tracking-tight">39</p>
                </div>
                <div class="mt-6 pt-4 border-t border-white/20">
                    <p class="text-xs text-white/70 tracking-wider">Tahun 2023</p>
                </div>
            </div>
        </a>

        <a href="/wilayah/kota-medan" data-type="kota" data-nama="kota medan" class="wilayah-card group block bg-[#234B26] text-white rounded-2xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:bg-[#1a381c]">
            <div class="flex flex-col h-full justify-between">
                <div>
                    <h3 class="text-lg font-semibold tracking-wide min-h-[3.5rem]">Kota Medan</h3>
                    <p class="text-5xl font-bold mt-2 text-[#E6EB9C] tracking-tight">39</p>
                </div>
                <div class="mt-6 pt-4 border-t border-white/20">
                    <p class="text-xs text-white/70 tracking-wider">Tahun 2020</p>
                </div>
            </div>
        </a>

        <a href="/wilayah/kabupaten-asahan" data-type="kabupaten" data-nama="kabupaten asahan" class="wilayah-card group block bg-[#234B26] text-white rounded-2xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:bg-[#1a381c]">
            <div class="flex flex-col h-full justify-between">
                <div>
                    <h3 class="text-lg font-semibold tracking-wide min-h-[3.5rem]"> Kabupaten Humbang Hasundutan </h3>
                    <p class="text-5xl font-bold mt-2 text-[#E6EB9C] tracking-tight">39</p>
                </div>
                <div class="mt-6 pt-4 border-t border-white/20">
                    <p class="text-xs text-white/70 tracking-wider">Tahun 2023</p>
                </div>
            </div>
        </a>

        <a href="/wilayah/kabupaten-deli-serdang" data-type="kabupaten" data-nama="kabupaten deli serdang" class="wilayah-card group block bg-[#234B26] text-white rounded-2xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:bg-[#1a381c]">
            <div class="flex flex-col h-full justify-between">
                <div>
                    <h3 class="text-lg font-semibold tracking-wide min-h-[3.5rem]">Kabupaten Deli Serdang</h3>
                    <p class="text-5xl font-bold mt-2 text-[#E6EB9C] tracking-tight">39</p>
                </div>
                <div class="mt-6 pt-4 border-t border-white/20">
                    <p class="text-xs text-white/70 tracking-wider">Tahun 2021</p>
                </div>
            </div>
        </a>

        </div>

    <div id="noDataMessage" class="hidden text-center py-12 text-gray-500">
        Data Kabupaten/Kota tidak ditemukan.
    </div>

    <div class="flex items-center justify-center space-x-2 mt-8">
        <button onclick="prevPage()" id="prevBtn" class="page-btn flex items-center justify-center transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <div id="paginationNumbers" class="flex space-x-2"></div>

        <button onclick="nextPage()" id="nextBtn" class="page-btn flex items-center justify-center transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
    </div>
</div>

<style>
.active-tab {
    background: #234B26;
    color: white;
}

.inactive-tab {
    background: white;
    color: #234B26;
}

/* Gaya Tombol Buat Lingkaran Pagination Sesuai Code Anda */
.page-btn {
    background: #234B26;
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 999px;
}
.page-btn:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}
</style>

<script>
let currentPage = 1;
let perPage = 12; // Jumlah item card per halaman (sesuai mockup isi 3 baris x 4 kolom)
let currentWilayahFilter = 'semua';

/* =======================================
   SYSTEM TAB FILTER (SEMUA, KOTA, KAB)
======================================= */
function filterWilayah(type) {
    currentWilayahFilter = type;

    // Ganti class active/inactive tombol tab filter
    document.querySelectorAll('.filter-btn').forEach(el => {
        el.classList.remove('active-tab');
        el.classList.add('inactive-tab');
    });

    const activeBtn = document.getElementById('btn-' + type);
    activeBtn.classList.add('active-tab');
    activeBtn.classList.remove('inactive-tab');

    // Terapkan gabungan filter wilayah & search kata kunci
    applyCombinedFilter();
}

/* =======================================
   SEARCH SYSTEM
======================================= */
function searchCards() {
    applyCombinedFilter();
}

/* =======================================
   COMBINED FILTER CORE (MENGGABUNGKAN TAB & SEARCH)
======================================= */
function applyCombinedFilter() {
    const keyword = document.getElementById("searchInput").value.toLowerCase();

    document.querySelectorAll(".wilayah-card").forEach(card => {
        const namaWilayah = card.dataset.nama.toLowerCase();
        const tipeWilayah = card.dataset.type;

        // Cek kecocokan Tab Jenis Wilayah
        const matchTab = (currentWilayahFilter === 'semua' || tipeWilayah === currentWilayahFilter);
        // Cek kecocokan Keyword Search
        const matchSearch = namaWilayah.includes(keyword);

        if (matchTab && matchSearch) {
            card.dataset.filtered = "show";
        } else {
            card.dataset.filtered = "hidden";
        }
    });

    currentPage = 1; // Kembalikan ke page 1 setiap kali filter berubah
    renderCards();
}

/* =======================================
   PAGINATION MANAGEMENT FOR CARDS
======================================= */
function getVisibleCards() {
    return [...document.querySelectorAll(".wilayah-card")]
        .filter(card => card.dataset.filtered !== "hidden");
}

function renderCards() {
    const visibleCards = getVisibleCards();
    const totalPages = Math.ceil(visibleCards.length / perPage) || 1;

    if (currentPage > totalPages) currentPage = totalPages;

    // Sembunyikan semua card terlebih dahulu
    document.querySelectorAll(".wilayah-card").forEach(card => card.classList.add("hidden"));

    const start = (currentPage - 1) * perPage;
    const end = start + perPage;

    // Tampilkan hanya card yang masuk dalam rentang indeks halaman aktif
    visibleCards.forEach((card, i) => {
        if (i >= start && i < end) card.classList.remove("hidden");
    });

    // Handle Tampilan State Kosong
    const noDataMsg = document.getElementById("noDataMessage");
    if (visibleCards.length === 0) {
        noDataMsg.classList.remove("hidden");
    } else {
        noDataMsg.classList.add("hidden");
    }

    renderCardPagination(visibleCards.length);
}

function renderCardPagination(totalData) {
    const totalPages = Math.ceil(totalData / perPage) || 1;
    const container = document.getElementById("paginationNumbers");

    container.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement("button");
        btn.innerText = i;

        // Menggunakan paduan style warna dari asset code anda
        btn.className = i === currentPage
            ? "w-10 h-10 rounded-xl bg-[#E6EB9C] text-[#234B26] border border-[#234B26] font-bold transition-all"
            : "w-10 h-10 rounded-xl border border-gray-300 text-gray-700 bg-white hover:bg-[#E6EB9C] hover:text-[#234B26] transition-all";

        btn.onclick = () => {
            currentPage = i;
            renderCards();
        };

        container.appendChild(btn);
    }

    document.getElementById("prevBtn").disabled = currentPage === 1;
    document.getElementById("nextBtn").disabled = currentPage === totalPages;
}

function nextPage() {
    const totalPages = Math.ceil(getVisibleCards().length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderCards();
    }
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        renderCards();
    }
}

/* =======================================
   INITIAL RUN ON LOAD
======================================= */
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".wilayah-card").forEach(card => {
        card.dataset.filtered = "show";
    });
    renderCards();
});
</script>

@endsection
