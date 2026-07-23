@extends('app')

@section('content')

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
        <span>✅</span> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
        ❌ {{ session('error') }}
    </div>
@endif

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
                Geospasial
            </button>

            <button onclick="switchTab('validasi')" id="btn-validasi"
                class="tab-btn inactive-tab px-14 py-3 font-semibold">
                Validasi
            </button>

        </div>

        <!-- Download -->
        <div class="flex justify-between items-center gap-2">

    <!-- Tambah Data
   <a href="{{ route('admin.add') }}" class="bg-[#234B26] text-white px-6 py-3 rounded-xl font-medium hover:opacity-90">
    + Tambah Data
</a> -->

    <!-- Download -->
    <div class="flex items-center gap-2">
           <a href="{{ route('admin.add') }}" class="bg-[#008001] text-white px-5 py-3 rounded-xl font-medium hover:opacity-90">+ Tambah Data</a>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="bg-[#0F2AF4] text-white px-5 py-3 rounded-xl font-medium hover:opacity-90 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v10m0 0l-4-4m4 4l4-4m-9 8h10" />
                    </svg>
                    <span>Download</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 bg-white rounded-xl shadow-xl border border-gray-200 z-50 overflow-hidden min-w-[160px]">
                    <a href="{{ route('admin.export.pdf') }}" class="flex items-center gap-2 px-4 py-3 text-[#234B26] hover:bg-[#D7E3D4] text-sm font-medium">Export PDF</a>
                    <a href="{{ route('admin.export.excel') }}" class="flex items-center gap-2 px-4 py-3 text-[#234B26] hover:bg-[#D7E3D4] text-sm font-medium">Export Excel</a>
                </div>
            </div>
        </div>

</div>

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
                            <th class="pb-4">Prioritas</th>
                            <th class="pb-4">Foto</th>
                            <th class="pb-4">Tahun</th>

                        </tr>

                    </thead>

                <tbody id="tableBody">
    @forelse($blankSpots as $i => $spot)
    <tr class="border-b border-gray-200 hover:bg-[#F3F3E8]/50 transition">
        <td class="py-3 pl-5 text-center">{{ $loop->iteration }}</td>
        <td class="py-3">{{ $spot->kabupaten->nama_kabupaten ?? '-' }}</td>
        <td class="py-3">{{ $spot->kecamatan->nama_kecamatan ?? '-' }}</td>
        <td class="py-3">{{ $spot->desa->nama_desa ?? '-' }}</td>
        <td class="py-3">{{ $spot->longitude }}</td>
        <td class="py-3">{{ $spot->latitude }}</td>
        <td class="py-3 font-bold text-amber-800">{{ $spot->prioritas ? 'P' . $spot->prioritas : '-' }}</td>
        <td class="py-3">
            @if($spot->foto)
                <a href="{{ asset('storage/' . $spot->foto) }}" target="_blank" class="text-blue-600 underline font-semibold text-xs">Lihat Foto</a>
            @else
                <span class="text-gray-400">-</span>
            @endif
        </td>
        <td class="py-3 text-center">{{ $spot->tahun }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="9" class="text-center py-8 text-gray-400">Belum ada data blank spot.</td>
    </tr>
    @endforelse
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
                        @foreach($kabupatens ?? [] as $kab)
                            <option value="{{ $kab->id }}">{{ $kab->nama_kabupaten }}</option>
                        @endforeach
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
                        @foreach($tahunList ?? [] as $tahun)
                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endforeach
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

            <button onclick="resetGeospatial()"
                class="w-full bg-gray-500 text-white hover:bg-gray-600 font-bold py-3 px-4 rounded-xl transition duration-200 shadow-md tracking-wider text-sm mt-2">
                Reset Peta
            </button>

        </div>

        <div class="w-full lg:w-3/4 bg-[#F3F3E8] rounded-3xl p-4 shadow-2xl border border-[#234B26]/10">
            <div id="map" class="w-full h-[500px] rounded-2xl z-10 shadow-inner"></div>
        </div>

    </div>
</div>

   <!-- ======================================== -->
    <!-- TAB VALIDASI - FINAL -->
    <!-- ======================================== -->
    <div id="content-validasi" class="tab-content hidden mt-8">
        
        <!-- Statistik Validasi -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-[#FFE200] border border-yellow-200 rounded-2xl p-6 shadow-sm transition-all hover:shadow-md">
                <p class="font-semibold text-[20px] text-white">Menunggu Validasi</p>
                <h3 class="text-5xl font-bold my-2 text-white" data-counter="menunggu">{{ $totalMenunggu ?? 0 }}</h3>
                <p class="font-semibold text-[20px] text-white">Data</p>
            </div>
            <div class="bg-[#008001] border border-green-200 rounded-2xl p-6 shadow-sm transition-all hover:shadow-md">
                <p class="font-semibold text-[20px] text-white">Disetujui</p>
                <h3 class="text-5xl font-bold my-2 text-white" data-counter="disetujui">{{ $totalDisetujui ?? 0 }}</h3>
                <p class="font-semibold text-[20px] text-white">Data</p>
            </div>
            <div class="bg-[#E30304] border border-red-900 rounded-2xl p-6 text-black shadow-sm transition-all hover:shadow-md">
                <p class="font-semibold text-[20px] text-white">Ditolak</p>
                <h3 class="text-5xl font-bold my-2 text-white" data-counter="ditolak">{{ $totalDitolak ?? 0 }}</h3>
                <p class="font-semibold text-[20px] text-white">Data</p>
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
                <!-- Tahun -->
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

              <h4 id="judulValidasi"
    class="text-[#234B26] font-bold text-2xl mb-6 border-b border-gray-300/60 pb-3">
    Daftar Data 
</h4>
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
                        <th class="px-4 py-3.5">Prioritas</th>
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
                           <span class="font-bold text-amber-800">{{ $spot->prioritas ? 'P' . $spot->prioritas : '-' }}</span>
                        </td>
                        <td class="px-4 py-3.5 text-center">{{ $spot->tahun }}</td>
                        <td class="px-4 py-3.5 text-gray-500">{{ $spot->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3.5 aksi-cell" data-id="{{ $spot->id }}">
                            <div class="flex justify-center gap-2">
                                <button onclick="event.stopPropagation(); editData({{ $spot->id }})"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.25 2.25 0 113.182 3.182L7.5 20.213 3 21l.787-4.5L16.862 4.487z" />
                                    </svg>
                                </button>
                                <button onclick="event.stopPropagation(); hapusData({{ $spot->id }})"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-200">
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
                            <tr><td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Prioritas</td><td id="detail-status" class="px-4 py-3">-</td></tr>
                            <tr><td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Foto</td><td id="detail-status" class="px-4 py-3">-</td></tr>
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
        <div id="aksiValidasi" class="flex flex-wrap items-center justify-center gap-4 mt-8 pt-6 border-t border-gray-300/60">
            <button type="button" onclick="aksiTolak()"
                class="flex items-center gap-2 bg-[#E30304] hover:bg-red-800 text-white px-8 py-3.5 rounded-xl font-bold shadow-md transition-transform transform active:scale-95 text-base">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
                Tolak
            </button>
            <button type="button" onclick="aksiSetujui()"
                class="flex items-center gap-2 bg-[#008001] hover:bg-[#1a381c] text-white px-8 py-3.5 rounded-xl font-bold shadow-md transition-transform transform active:scale-95 text-base">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Setujui
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

/* Style dropdown */
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

/* ============================================================
   FILTER VALIDASI TABLE
============================================================ */
function filterValidasiTable() {
    const kabupaten = document.getElementById('filterKabupaten')?.value || '';
    const status = document.getElementById('filterStatus')?.value || '';
    const tahun = document.getElementById('filterTahun')?.value || '';
    const cari = document.getElementById('filterCari')?.value?.toLowerCase() || '';

    // Ganti judul
    const judul = document.getElementById('judulValidasi');

    if (status === 'approved') {
        judul.innerText = 'Daftar Data Disetujui';
    } else if (status === 'rejected') {
        judul.innerText = 'Daftar Data Ditolak';
    } else {
        judul.innerText = 'Daftar Data Menunggu Validasi';
    }

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

// Event listener untuk filter otomatis
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

// ============================================================
// GRAFIK (CHART.JS) - PERBAIKAN DARI DATABASE
// ============================================================
let myChart = null;

// Data dari database (bukan hardcoded)
const chartData = {
    labels: @json($tahunLabels ?? []),
    datasets: [{
        label: 'Jumlah Blank Spot',
        data: @json($tahunCounts ?? []),
        backgroundColor: '#86EFAC',
        borderColor: '#86EFAC',
        borderWidth: 1,
        borderRadius: 8
    }]
};

function initChart(type = 'bar') {
    const ctx = document.getElementById('blankspotChart').getContext('2d');
    
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
                        display: false
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
                    ticks: {
                        stepSize: 5,
                        color: '#234B26',
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(35, 75, 38, 0.1)'
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
let markersLayer = L.layerGroup();

// Data dari server
const spotsData = @json($spotsPeta ?? []);
const blankspotLocations = spotsData.map(function(s) {
    return {
        name: (s.kecamatan ? s.kecamatan.nama_kecamatan : '-') + ', ' + (s.desa ? s.desa.nama_desa : '-'),
        lat: parseFloat(s.latitude),
        lng: parseFloat(s.longitude),
        year: s.tahun,
        kab: s.kabupaten_id,
        status: s.keterangan || 'Blank Spot',
        kabupaten: s.kabupaten ? s.kabupaten.nama_kabupaten : '-'
    };
});

function initMap() {
    if (map !== null) {
        map.invalidateSize();
        return;
    }

    map = L.map('map').setView([2.5, 99.0], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    markersLayer.addTo(map);
    renderMarkers(blankspotLocations);
}

function renderMarkers(locations) {
    markersLayer.clearLayers();

    if (!locations || locations.length === 0) {
        return;
    }

    locations.forEach(function(loc) {
        if (loc.lat && loc.lng && !isNaN(loc.lat) && !isNaN(loc.lng)) {
            const marker = L.marker([loc.lat, loc.lng])
                .bindPopup(`<b>${loc.name}</b><br>Tahun: ${loc.year}<br>Status: ${loc.status || '-'}`);
            markersLayer.addLayer(marker);
        }
    });
}

function filterGeospatial() {
    const region = document.getElementById("geoRegion").value;
    const year = document.getElementById("geoYear").value;

    if (!map) {
        alert('Peta belum siap. Silakan tunggu sebentar.');
        return;
    }

    let filtered = blankspotLocations;

    if (region && region !== 'all') {
        filtered = filtered.filter(function(loc) {
            return loc.kab == region;
        });
    }

    if (year) {
        filtered = filtered.filter(function(loc) {
            return loc.year == year;
        });
    }

    if (filtered.length === 0) {
        alert('Tidak ada data untuk filter yang dipilih.');
        renderMarkers([]);
        return;
    }

    renderMarkers(filtered);

    try {
        var bounds = filtered.map(function(loc) {
            return [loc.lat, loc.lng];
        });
        map.fitBounds(bounds, { padding: [50, 50] });
    } catch(e) {}
}

function resetGeospatial() {
    document.getElementById('geoRegion').value = 'all';
    document.getElementById('geoYear').value = '';

    if (!map) {
        alert('Peta belum siap.');
        return;
    }

    renderMarkers(blankspotLocations);
    map.setView([2.5, 99.0], 7);
    map.invalidateSize();
}

const baseSwitchTab = switchTab; 
switchTab = function(tab) {
    baseSwitchTab(tab); 
    if (tab === 'geo') {
        setTimeout(function() {
            initMap();
        }, 100);
    }
}

/* =========================
   VALIDASI MAP
========================= */
let validasiMap = null;
let detailMarker = null;

function initValidasiMap() {

    if (validasiMap !== null) {
        setTimeout(function() {
            validasiMap.invalidateSize();
        }, 100);
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

    if (!validasiMap) {
        initValidasiMap();
    }

    if (detailMarker) {
        validasiMap.removeLayer(detailMarker);
    }

    detailMarker = L.marker([lat, lng])
        .addTo(validasiMap)
        .bindPopup(
            `<b>${id}</b><br>${status}`
        )
        .openPopup();

    validasiMap.setView([lat, lng], 15);

    setTimeout(function() {
        validasiMap.invalidateSize();
    }, 200);
}

/* =========================
   VALIDASI - SWITCH TAB
========================= */
const oldSwitchTab = switchTab;

switchTab = function(tab) {
    oldSwitchTab(tab);

    if (tab === 'validasi') {
        setTimeout(function() {
            initValidasiMap();
            if (validasiMap) {
                validasiMap.invalidateSize();
            }
        }, 150);
    }

    if (tab === 'geo') {
        setTimeout(function() {
            initMap();
        }, 100);
    }
}

/* =========================
   VALIDASI - FILTER TABLE
========================= */
function filterValidasiTable() {
    const kabupaten = document.getElementById('filterKabupaten')?.value || '';
    const status = document.getElementById('filterStatus')?.value || '';
    const tahun = document.getElementById('filterTahun')?.value || '';
    const cari = document.getElementById('filterCari')?.value?.toLowerCase() || '';

const aksi = document.getElementById('aksiValidasi');

if (status === 'pending') {
    aksi.classList.remove('hidden');
} else {
    aksi.classList.add('hidden');
}

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

/* =========================
   VALIDASI - PILIH ROW & DETAIL
========================= */
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

/* =========================
   AKSI VALIDASI - SETUJUI & TOLAK
========================= */

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
            // Hapus row
            const row = document.getElementById('row-' + activeSpotId);
            if (row) { row.style.display = 'none'; }
            
            // Update counter
            const menunggu = document.querySelector('[data-counter="menunggu"]');
            if (menunggu) { menunggu.textContent = Math.max(0, parseInt(menunggu.textContent) - 1); }
            const disetujui = document.querySelector('[data-counter="disetujui"]');
            if (disetujui) { disetujui.textContent = parseInt(disetujui.textContent) + 1; }
            
            document.getElementById('detailSection')?.classList.add('hidden');
            activeSpotId = null;
            
            alert(data.message || 'Data berhasil disetujui!');
            
            // Refresh halaman dashboard setelah approve
            setTimeout(function() {
                window.location.href = '/admin/dashboard';
            }, 1000);
        } else {
            alert('Error: ' + (data.message || 'Silakan coba lagi.'));
        }
    })
    .catch(error => { alert('Gagal terhubung ke server.'); });
}


function aksiTolak() {
    if (!activeSpotId) {
        alert('Pilih data terlebih dahulu dengan mengklik baris pada tabel.');
        return;
    }

    if (!confirm('Apakah Anda yakin ingin menolak data ini?')) return;

    const endpoint = '/admin/validasi/' + activeSpotId + '/tolak';

    fetch(endpoint, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.success) {
            const row = document.getElementById('row-' + activeSpotId);
            if (row) {
                row.style.transition = 'opacity 0.3s';
                row.style.opacity = '0';
                setTimeout(function() { row.remove(); }, 300);
            }

            const menunggu = document.querySelector('[data-counter="menunggu"]');
            if (menunggu) {
                menunggu.textContent = Math.max(0, parseInt(menunggu.textContent) - 1);
            }
            const ditolak = document.querySelector('[data-counter="ditolak"]');
            if (ditolak) {
                ditolak.textContent = parseInt(ditolak.textContent) + 1;
            }

            document.getElementById('detailSection')?.classList.add('hidden');
            activeSpotId = null;
            
            alert(data.message || 'Data berhasil ditolak!');
            
            setTimeout(function() {
                location.reload();
            }, 1500);
        } else {
            alert('Error: ' + (data.message || 'Silakan coba lagi.'));
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Gagal terhubung ke server.');
    });
}

/* =========================
   EDIT & HAPUS DATA
========================= */

function editData(id) {
    const row = document.querySelector(`.aksi-cell[data-id="${id}"]`)?.closest('tr');
    if (!row) {
        alert('Data tidak ditemukan');
        return;
    }
    const realId = row.id.replace('row-', '');
    if (!realId || realId === '') {
        alert('ID data tidak valid');
        return;
    }
    window.location.href = '/admin/validasi/' + realId + '/edit';
}

function hapusData(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus data ini secara permanen?')) {
        return;
    }

    const row = document.querySelector(`.aksi-cell[data-id="${id}"]`)?.closest('tr');
    if (!row) {
        alert('Data tidak ditemukan');
        return;
    }

    const realId = row.id.replace('row-', '');
    if (!realId || realId === '') {
        alert('ID data tidak valid');
        return;
    }

    fetch('/admin/validasi/' + realId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.success) {
            row.style.transition = 'opacity 0.3s';
            row.style.opacity = '0';
            setTimeout(function() { row.remove(); }, 300);

            const menunggu = document.querySelector('[data-counter="menunggu"]');
            if (menunggu) {
                const current = parseInt(menunggu.textContent) || 0;
                menunggu.textContent = Math.max(0, current - 1);
            }

            alert(data.message || 'Data berhasil dihapus!');
            setTimeout(function() { location.reload(); }, 1000);
        } else {
            alert('Error: ' + (data.message || 'Silakan coba lagi.'));
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Gagal menghapus data. Silakan coba lagi.');
    });
}

</script>

@endsection