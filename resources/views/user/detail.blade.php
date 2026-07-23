@extends('app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center gap-2 mb-8">
    {{-- Tombol Home --}}
    <a href="{{ route('user.dashboard') }}"
        class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#234B26] text-white hover:bg-[#1a381c] transition shadow-md">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 12 11.204 3.046a1.125 1.125 0 0 1 1.592 0L21.75 12M4.5 9.75V19.5A1.5 1.5 0 0 0 6 21h3.75v-4.5A1.5 1.5 0 0 1 11.25 15h1.5a1.5 1.5 0 0 1 1.5 1.5V21H18a1.5 1.5 0 0 0 1.5-1.5V9.75" />
        </svg>
    </a>

    {{-- Tombol Kembali --}}
    <a href="{{ route('user.add') }}"
        class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#234B26] text-white hover:bg-[#1a381c] transition shadow-md">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
        <h2 class="text-3xl ml-3 font-bold text-[#234B26]">{{ $kabupaten->nama_kabupaten }}</h2>
        @if(!$isOwner)
            <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">Hanya Lihat</span>
        @endif
    </div>
    

    <div id="content-table" class="tab-content mt-10">
        <div class="bg-[#F3F3E8] rounded-3xl shadow-2xl p-8">

            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="text-[#234B26] font-bold text-2xl">Tampilkan</span>
                    <div class="relative w-fit">
                        <select id="entries" onchange="changeEntries()"
                            class="appearance-none bg-[#234B26] text-white pl-3 pr-7 py-1.5 rounded-lg outline-none text-lg font-semibold cursor-pointer">
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

                <div class="flex items-center gap-3">
                    <div class="relative w-full md:w-80">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-[#234B26]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input id="searchInput" onkeyup="searchTable()" type="text"
                            placeholder="Cari kecamatan / desa..."
                            class="w-full md:w-80 border-2 border-[#234B26] rounded-xl pl-10 py-2 outline-none focus:ring-2 focus:ring-[#234B26]/20">
                    </div>
                    {{-- TOMBOL TAMBAH HANYA UNTUK KABUPATEN SENDIRI --}}
                    @if($isOwner)
                    <button onclick="openModal()" 
                        class="bg-[#234B26] text-white font-semibold px-5 py-2.5 rounded-xl hover:bg-[#1a381c] transition-colors shadow-sm flex items-center whitespace-nowrap gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah
                    </button>
                    @endif
                </div>
            </div>

            <div class="mt-10 overflow-x-auto">
                <table class="w-full text-sm text-left text-[#234B26] border-collapse">
                    <thead class="border-b-2 border-[#234B26] bg-[#D7E3D4]">
                        <tr>
                            <th class="px-4 py-3 text-center font-bold">No</th>
                            <th class="px-4 py-3 font-bold">Nama Kecamatan</th>
                            <th class="px-4 py-3 font-bold">Nama Desa</th>
                            <th class="px-4 py-3 font-bold">Longitude</th>
                            <th class="px-4 py-3 font-bold">Latitude</th>
                            <th class="px-3 py-3 font-bold">Prioritas</th>
                            <th class="px-3 py-3 font-bold">Foto</th>
                            <th class="px-4 py-3 font-bold">Tahun</th>
                            <th class="px-4 py-3 text-center font-bold">Status</th>
                            <th class="px-4 py-3 text-center font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($blankSpots as $i => $spot)
                        <tr class="border-b border-gray-200 hover:bg-[#F3F3E8]/50 transition">
                            <td class="px-4 py-3 text-center">{{ $blankSpots->firstItem() + $i }}</td>
                            <td class="px-4 py-3">{{ $spot->kecamatan->nama_kecamatan ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $spot->desa->nama_desa ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $spot->longitude }}</td>
                            <td class="px-4 py-3">{{ $spot->latitude }}</td>
                            <td class="px-4 py-3">{{ $spot->tahun }}</td>
                            <td class="px-4 py-3">{{ $spot->tahun }}</td>
                            <td class="px-4 py-3">{{ $spot->tahun }}</td>
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
                                    {{-- EDIT & HAPUS HANYA UNTUK KABUPATEN SENDIRI DAN STATUS PENDING --}}
                                    @if($isOwner && $spot->status_validasi != 'approved')
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
                                    <form action="{{ route('user.blank-spot.destroy', $spot->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition" title="Hapus">
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
                            <td colspan="8" class="text-center py-8 text-gray-400">Belum ada data untuk kabupaten ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-4 mt-10">
                <p class="text-lg font-medium text-[#234B26]">
                    Menampilkan <span id="showStart">{{ $blankSpots->firstItem() ?? 0 }}</span> - 
                    <span id="showEnd">{{ $blankSpots->lastItem() ?? 0 }}</span> 
                    dari <span id="showTotal">{{ $blankSpots->total() }}</span> data
                </p>
                <div>{{ $blankSpots->links() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH DATA (HANYA UNTUK KABUPATEN SENDIRI) -->
@if($isOwner)
<div id="blankspotModal"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">

    <div class="bg-[#234B26] w-full max-w-md p-6 rounded-3xl shadow-2xl border border-white/10 mx-4 transform scale-95 transition-transform duration-300" id="modalContent">

        <div class="text-center mb-4">
            <h3 class="text-xl font-bold text-[#E6EB9C]">Masukkan Data</h3>
            <p class="text-xl italic font-bold text-[#E6EB9C]">Blankspot</p>
        </div>

        <form action="{{ route('user.blank-spot.store') }}" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="kabupaten_id" value="{{ $kabupaten->id }}">

            <div>
                <label class="block text-white font-semibold mb-1.5 text-sm">Kecamatan</label>
                <select name="kecamatan_id" class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30 appearance-none" required
                        style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; padding-right: 36px;">
                    <option value="">-- Pilih Kecamatan --</option>
                    @foreach($kecamatans ?? [] as $kec)
                        <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-white font-semibold mb-1.5 text-sm">Nama Desa</label>
                <input type="text" name="nama_desa" placeholder="Ketik nama desa..." required
                       class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
            </div>

            <!-- KOORDINAT -->
<div class="grid grid-cols-2 gap-2.5">
    <!-- LONGITUDE -->
    <div>
        <label class="block text-white font-semibold mb-1.5 text-sm">Longitude</label>
        <input type="text" name="longitude" placeholder="Contoh: 98.6722" required
               class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
    </div>

    <!-- LATITUDE -->
    <div>
        <label class="block text-white font-semibold mb-1.5 text-sm">Latitude</label>
        <input type="text" name="latitude" placeholder="Contoh: 3.5952" required
               class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
    </div>
</div>

                        <!-- KETERANGAN & TAHUN -->
<div class="grid grid-cols-4 gap-2.5">

    <!-- KETERANGAN (lebih panjang) -->
    <div class="col-span-3">
        <label class="block text-white font-semibold mb-1.5 text-sm">
            Status
        </label>

        <select name="keterangan"
                class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30 appearance-none"
                required
                style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; padding-right: 36px;">

            <option value="">-- Pilih Prioritas --</option>
            <option value="Priority 1">
                Prioritas 1 
            </option>
            <option value="Priority 2">
                Prioritas 2 
            </option>
            <option value="Priority 3">
                Prioritas 3 
            </option>
            <option value="Priority 4">
                Prioritas 4 
            </option>
            <option value="Priority 5">
                Prioritas 5
            </option>
            <option value="Priority 5">
                Prioritas 6
            </option>
            <option value="Priority 2">
                Prioritas 7 
            </option>
            <option value="Priority 3">
                Prioritas 8 
            </option>
            <option value="Priority 4">
                Prioritas 9
            </option>
            <option value="Priority 5">
                Prioritas 10 (Bisa lebih dari 1)
            </option>

        </select>
        <p class="text-xs text-white/70 mt-1">
       Setiap prioritas hanya dapat dipilih satu kali untuk setiap kab/kota
    </p>
    </div>


    <!-- TAHUN (lebih kecil) -->
    <div class="col-span-1">
        <label class="block text-white font-bold text-sm mb-1.5">
            Tahun <span class="text-red-500">*</span>
        </label>

        <input
            type="text"
            value="{{ date('Y') }}"
            readonly
            class="w-full bg-[#F3F3E8] border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm text-gray-700 cursor-not-allowed"
        >

        <input
            type="hidden"
            name="tahun"
            value="{{ date('Y') }}">
    </div>

   <!-- FOTO -->
<div class="col-span-4 mt-1">
    <label class="block text-white font-semibold mb-1.5 text-sm">
        Foto Blankspot
    </label>

    <div class="flex">
        <!-- Nama file -->
        <div id="file-name"
             class="flex-1 bg-white text-gray-500 px-4 py-2.5 rounded-l-xl border-r border-gray-300 text-sm flex items-center">
            Belum ada file dipilih
        </div>

        <!-- Tombol -->
        <label for="foto"
               class="bg-[#E6EB9C] text-[#234B26] px-4 py-2.5 rounded-r-xl cursor-pointer hover:bg-[#F3F3E8] font-semibold text-sm flex items-center">
            Choose File
        </label>
    </div>

    <input
        type="file"
        id="foto"
        name="foto"
        accept="image/*"
        class="hidden"
        onchange="document.getElementById('file-name').textContent =
            this.files.length ? this.files[0].name : 'Belum ada file dipilih';"
    >

    <p class="text-xs text-white/70 mt-1">
        Format: JPG, JPEG, PNG. Maksimal 2 MB.
    </p>
</div>
</div>

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
@endif

<script>
let currentPage = 1;
let perPage = 5;

function openModal() {
    const modal = document.getElementById('blankspotModal');
    const content = document.getElementById('modalContent');
    modal.classList.remove('hidden');
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

window.onclick = function(event) {
    const modal = document.getElementById('blankspotModal');
    if (event.target == modal) {
        closeModal();
    }
}

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

// PASTIKAN MODAL TERTUTUP SAAT HALAMAN DIMUAT
document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById('blankspotModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.add('opacity-0');
    }
});
</script>

@endsection