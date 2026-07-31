@extends('app')

@section('content')
<div class="max-w-7xl mx-auto py-10">

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
    
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl font-semibold">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl font-semibold">
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

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
                        class="bg-[#008001] text-white font-semibold px-5 py-2.5 rounded-xl hover:bg-[#1a381c] transition-colors shadow-sm flex items-center whitespace-nowrap gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah
                    </button>
                    <a href="{{ route('user.export.pdf') }}" class="bg-[#0F2AF4] text-white px-4 py-2.5 rounded-xl font-medium hover:opacity-90 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v10m0 0l-4-4m4 4l4-4m-9 8h10" />
                        </svg>
                        <span>Download</span>
                    </a>
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
                            <th class="px-3 py-3 font-bold">Status Jaringan</th>
                            <th class="px-3 py-3 font-bold">Kondisi<br>Geografis</th>
                            <th class="px-3 py-3 font-bold">Jumlah<br>Penduduk</th>
                            <th class="px-3 py-3 font-bold">Jarak ke<br>Ibu Kota</th>
                            <th class="px-4 py-3 font-bold text-center">Tahun</th>
                            <th class="px-4 py-3 text-center font-bold">Status</th>
                            <th class="px-4 py-3 text-center font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($blankSpots as $i => $spot)
                        <tr class="border-b border-gray-200 hover:bg-[#F3F3E8]/50 transition cursor-pointer"
                            onclick="showDetail(this)"
                            data-id="BS-{{ $spot->tahun }}-{{ str_pad($spot->id, 4, '0', STR_PAD_LEFT) }}"
                            data-kabupaten="{{ $kabupaten->nama_kabupaten }}"
                            data-kecamatan="{{ $spot->kecamatan->nama_kecamatan ?? '-' }}"
                            data-desa="{{ $spot->desa->nama_desa ?? '-' }}"
                            data-longitude="{{ $spot->longitude }}"
                            data-latitude="{{ $spot->latitude }}"
                            data-prioritas="{{ $spot->prioritas ? 'P' . $spot->prioritas : '-' }}"
                            data-status-jaringan="{{ $spot->status_jaringan ?? '-' }}"
                            data-kondisi-geografis="{{ $spot->kondisi_geografis ?? '-' }}"
                            data-jumlah-penduduk="{{ $spot->jumlah_penduduk ? (is_numeric($spot->jumlah_penduduk) ? number_format((float)$spot->jumlah_penduduk) . ' Jiwa' : $spot->jumlah_penduduk) : '-' }}"
                            data-jarak-ibukota="{{ $spot->jarak_ibukota ? $spot->jarak_ibukota . ' Km' : '-' }}"
                            data-tahun="{{ $spot->tahun }}"
                            data-operator="{{ $spot->creator->nama ?? $spot->creator->name ?? '-' }}"
                            data-tanggal="{{ $spot->created_at->format('d M Y, H:i') }} WIB"
                            data-status="{{ $spot->status_label }}"
                            data-catatan-revisi="{{ $spot->catatan_revisi }}"
                            data-keterangan="{{ $spot->status_jaringan ?? $spot->keterangan ?? '-' }}"
                            data-photos='@json($spot->photos->map(fn($p) => ["id" => $p->id, "url" => $p->url, "jenis" => $p->jenis_foto]))'>
                            <td class="px-4 py-3 text-center">{{ $blankSpots->firstItem() + $i }}</td>
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
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center items-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $spot->status_badge }}">
                                        {{ $spot->status_label }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    @if($isOwner && $spot->status_validasi != 'approved')
                                    <a href="{{ route('user.blank-spot.edit', $spot->id) }}" 
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.25 2.25 0 113.182 3.182L7.5 20.213 3 21l.787-4.5L16.862 4.487z" />
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
                            <td colspan="13" class="text-center py-8 text-gray-400">Belum ada data untuk kabupaten ini.</td>
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
    @include('partials.blank-spot-detail-card')
</div>

<!-- MODAL TAMBAH DATA -->
@if($isOwner)
<div id="blankspotModal"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">

    <div class="bg-[#234B26] w-full max-w-md max-h-[90vh] flex flex-col p-6 rounded-3xl shadow-2xl border border-white/10 mx-4 transform scale-95 transition-transform duration-300" id="modalContent">

        <div class="text-center mb-3 shrink-0">
            <h3 class="text-xl font-bold text-[#E6EB9C]">Masukkan Data</h3>
            <p class="text-xl italic font-bold text-[#E6EB9C]">Blankspot</p>
        </div>

        <form action="{{ route('user.blank-spot.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 min-h-0">
            @csrf
            <input type="hidden" name="kabupaten_id" value="{{ $kabupaten->id }}">

            <!-- SCROLLABLE FORM BODY -->
            <div class="overflow-y-auto pr-1 space-y-3 flex-1 custom-scrollbar" style="max-height: calc(90vh - 150px);">

                <!-- KECAMATAN -->
                <div>
                    <label class="block text-white font-semibold mb-1 text-sm">Kecamatan <span class="text-red-400">*</span></label>
                    <select name="kecamatan_id" class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30 appearance-none" required
                            style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; padding-right: 36px;">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($kecamatans ?? [] as $kec)
                            <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- DESA -->
                <div>
                    <label class="block text-white font-semibold mb-1 text-sm">Nama Desa <span class="text-red-400">*</span></label>
                    <input type="text" name="nama_desa" placeholder="Ketik nama desa..." required
                           class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
                </div>

                <!-- KOORDINAT -->
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-white font-semibold mb-1 text-sm">Longitude <span class="text-red-400">*</span></label>
                        <input type="text" name="longitude" placeholder="Contoh: 98.6722" required
                               class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-1 text-sm">Latitude <span class="text-red-400">*</span></label>
                        <input type="text" name="latitude" placeholder="Contoh: 3.5952" required
                               class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
                    </div>
                </div>

                <!-- PRIORITAS & KONDISI GEOGRAFIS -->
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-white font-semibold mb-1 text-sm">Prioritas <span class="text-red-400">*</span></label>
                        <select name="prioritas"
                                class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30 appearance-none"
                                required
                                style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; padding-right: 36px;">
                            <option value="">-- Pilih Prioritas --</option>
                            <option value="1">Zero Blankspot</option>
                            <option value="2">Sinyal Sangat Lemah</option>
                            <option value="3">Sinyal Lemah</option>
                            <option value="4">2G</option>
                            <option value="5">3G</option>
                            <option value="6">4G Tidak Stabil</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-1 text-sm">Kondisi Geografis <span class="text-red-400">*</span></label>
                        <select name="kondisi_geografis"
                                class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30 appearance-none"
                                required
                                style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; padding-right: 36px;">
                            <option value="">-- Pilih Kondisi --</option>
                            <option value="Pegunungan">Pegunungan</option>
                            <option value="Daerah Pantai">Daerah Pantai</option>
                            <option value="Daerah Sungai">Daerah Sungai</option>
                            <option value="Dataran Rendah">Dataran Rendah</option>
                            <option value="Perkebunan">Perkebunan</option>
                            <option value="Danau">Danau</option>
                            <option value="Perbukitan">Perbukitan</option>
                            <option value="Hutan">Hutan</option>
                            <option value="Pesisir">Pesisir</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <!-- JUMLAH PENDUDUK & JARAK -->
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-white font-semibold mb-1 text-sm">Jumlah Penduduk <span class="text-red-400">*</span></label>
                        <select name="jumlah_penduduk"
                                class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30 appearance-none"
                                required
                                style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; padding-right: 36px;">
                            <option value="">-- Pilih Jumlah Penduduk --</option>
                            <option value="1-10">1–10 orang</option>
                            <option value="11-50">11–50 orang</option>
                            <option value="51-100">51–100 orang</option>
                            <option value="101-200">101–200 orang</option>
                            <option value="201-500">201–500 orang</option>
                            <option value="501-1000">501–1.000 orang</option>
                            <option value="1001-5000">1.001–5.000 orang</option>
                            <option value="5001-10000">5.001–10.000 orang</option>
                            <option value="10001-50000">10.001–50.000 orang</option>
                            <option value="50000+">50.000+ orang</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-1 text-sm">Jarak ke Ibu Kota (Km) <span class="text-red-400">*</span></label>
                        <input type="number" name="jarak_ibukota" placeholder="Contoh: 25" min="0" required
                               class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
                    </div>
                </div>

                <!-- FOTO BLANKSPOT SECTION -->
                <div class="space-y-3">
                    <div>
                        <label class="block text-white font-semibold mb-1 text-sm">
                            Foto Dokumentasi Blankspot <span class="text-red-400">*</span>
                        </label>

                        <!-- FOTO BLANKSPOT 1 (Wajib) -->
                        <div id="user-photo-group-1" class="space-y-1">
                            <div class="flex items-center gap-2">
                                <img id="user-preview-1" class="w-10 h-10 object-cover rounded-xl hidden border border-white/20 shrink-0" alt="Preview">
                                <div id="user-file-name-1" class="flex-1 bg-white text-[#234B26]/60 px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent truncate flex items-center">
                                    Belum ada file dipilih
                                </div>
                                <label for="user-foto-input-1" class="bg-[#E6EB9C] text-[#234B26] px-4 py-2.5 rounded-xl cursor-pointer hover:bg-white font-semibold text-sm shrink-0 transition flex items-center justify-center">
                                    Choose File
                                </label>
                            </div>
                            <input type="file" id="user-foto-input-1" name="photos[]" accept="image/jpeg,image/jpg,image/png,image/webp" class="hidden" required onchange="handleUserPhotoPreview(this, 'user-file-name-1', 'user-preview-1');">
                        </div>
                    </div>

                    <!-- CONTAINER UNTUK DYNAMIC FOTO 2 S.D 10 -->
                    <div id="user-dynamic-photos-container" class="space-y-3"></div>

                    <!-- HELPER TEXT & ACTION BUTTON -->
                    <div>
                        <p class="text-xs text-white/70">Format: JPG, JPEG, PNG, WEBP. Min 1, Maks 10 Foto (Max 5MB/foto).</p>
                        <div class="flex items-center justify-between mt-2">
                            <button type="button" onclick="addUserPhotoField()" id="user-btn-add-photo" class="bg-[#E6EB9C] text-[#234B26] px-4 py-2.5 rounded-xl font-semibold text-sm hover:bg-white transition flex items-center gap-1.5 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Tambah Foto
                            </button>
                            <span id="user-photo-count-badge" class="text-xs text-white/80 font-medium">(1/10 foto)</span>
                        </div>
                    </div>
                </div>

                <!-- TAHUN -->
                <div>
                    <label class="block text-white font-bold text-sm mb-1">Tahun <span class="text-red-400">*</span></label>
                    <input type="text" value="{{ date('Y') }}" readonly class="w-full bg-[#F3F3E8] border border-[#234B26]/30 rounded-xl px-3 py-2.5 text-sm text-gray-700 cursor-not-allowed">
                    <input type="hidden" name="tahun" value="{{ date('Y') }}">
                </div>

            </div>

            <!-- BUTTON ACTIONS (FIXED AT BOTTOM OF MODAL) -->
            <div class="flex justify-end gap-3 pt-3 shrink-0 border-t border-white/10 mt-2">
                <button type="button" onclick="closeModal()" class="bg-white text-red-700 font-bold px-4 py-2 rounded-lg hover:bg-gray-200 text-sm transition">
                    Cancel
                </button>
                <button type="submit" class="bg-white text-[#234B26] font-bold px-4 py-2 rounded-lg hover:bg-gray-200 text-sm transition">
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

function handleUserPhotoPreview(input, labelId, previewId) {
    const file = input.files && input.files[0];
    const labelEl = document.getElementById(labelId);
    const previewEl = document.getElementById(previewId);

    if (file) {
        if (labelEl) labelEl.textContent = file.name;
        if (previewEl && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewEl.src = e.target.result;
                previewEl.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    } else {
        if (labelEl) labelEl.textContent = 'Belum ada file dipilih';
        if (previewEl) {
            previewEl.classList.add('hidden');
            previewEl.src = '';
        }
    }
}

let userPhotoCount = 1;
function addUserPhotoField() {
    if (userPhotoCount >= 10) {
        alert('Maksimal 10 foto yang dapat diunggah.');
        return;
    }
    userPhotoCount++;
    const container = document.getElementById('user-dynamic-photos-container');
    if (!container) return;

    const div = document.createElement('div');
    div.className = 'space-y-1';
    div.id = `user-photo-group-${userPhotoCount}`;
    div.innerHTML = `
        <div class="flex justify-between items-center mb-1">
            <label class="block text-white font-semibold text-xs">Foto Blankspot ${userPhotoCount}</label>
            <button type="button" onclick="document.getElementById('user-photo-group-${userPhotoCount}').remove(); userPhotoCount--; updateUserPhotoBtnState();" class="text-red-300 hover:text-white text-xs font-semibold">Hapus</button>
        </div>
        <div class="flex items-center gap-2">
            <img id="user-preview-${userPhotoCount}" class="w-10 h-10 object-cover rounded-xl hidden border border-white/20 shrink-0" alt="Preview">
            <div id="user-file-name-${userPhotoCount}" class="flex-1 bg-white text-[#234B26]/60 px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent truncate flex items-center">
                Belum ada file dipilih
            </div>
            <label for="user-foto-input-${userPhotoCount}" class="bg-[#E6EB9C] text-[#234B26] px-4 py-2.5 rounded-xl cursor-pointer hover:bg-white font-semibold text-sm shrink-0 transition flex items-center justify-center">
                Choose File
            </label>
        </div>
        <input type="file" id="user-foto-input-${userPhotoCount}" name="photos[]" accept="image/jpeg,image/jpg,image/png,image/webp" class="hidden" onchange="handleUserPhotoPreview(this, 'user-file-name-${userPhotoCount}', 'user-preview-${userPhotoCount}');">
    `;
    container.appendChild(div);
    updateUserPhotoBtnState();
}

function updateUserPhotoBtnState() {
    const btn = document.getElementById('user-btn-add-photo');
    const badge = document.getElementById('user-photo-count-badge');
    if (badge) badge.textContent = `(${userPhotoCount}/10 foto)`;
    if (btn) {
        if (userPhotoCount >= 10) {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
}

function showDetail(row) {
    const data = row.dataset;
    const lat = parseFloat(data.latitude);
    const lng = parseFloat(data.longitude);

    document.getElementById('detailSection').classList.remove('hidden');

    if(document.getElementById('detail-id')) document.getElementById('detail-id').innerText = data.id || '-';
    if(document.getElementById('detail-kabupaten')) document.getElementById('detail-kabupaten').innerText = data.kabupaten || '-';
    if(document.getElementById('detail-kecamatan')) document.getElementById('detail-kecamatan').innerText = data.kecamatan || '-';
    if(document.getElementById('detail-desa')) document.getElementById('detail-desa').innerText = data.desa || '-';
    if(document.getElementById('detail-longitude')) document.getElementById('detail-longitude').innerText = data.longitude || '-';
    if(document.getElementById('detail-latitude')) document.getElementById('detail-latitude').innerText = data.latitude || '-';
    if(document.getElementById('detail-prioritas')) document.getElementById('detail-prioritas').innerText = data.prioritas || '-';
    if(document.getElementById('detail-status-jaringan')) document.getElementById('detail-status-jaringan').innerText = data.statusJaringan || data.status_jaringan || '-';
    if(document.getElementById('detail-kondisi-geografis')) document.getElementById('detail-kondisi-geografis').innerText = data.kondisiGeografis || data.kondisi_geografis || '-';
    if(document.getElementById('detail-jumlah-penduduk')) document.getElementById('detail-jumlah-penduduk').innerText = data.jumlahPenduduk || data.jumlah_penduduk || '-';
    if(document.getElementById('detail-jarak-ibukota')) document.getElementById('detail-jarak-ibukota').innerText = data.jarakIbukota || data.jarak_ibukota || '-';
    if(document.getElementById('detail-tahun')) document.getElementById('detail-tahun').innerText = data.tahun || '-';
    if(document.getElementById('detail-operator')) document.getElementById('detail-operator').innerText = data.operator || '-';
    if(document.getElementById('detail-tanggal')) document.getElementById('detail-tanggal').innerText = data.tanggal || '-';
    if(document.getElementById('detail-status')) document.getElementById('detail-status').innerText = data.status || '-';
    if(document.getElementById('detail-keterangan')) document.getElementById('detail-keterangan').innerText = data.keterangan || '-';

    const catRow = document.getElementById('row-catatan-revisi');
    if (catRow) {
        if (data.catatanRevisi || data.catatan_revisi) {
            if (document.getElementById('detail-catatan-revisi')) document.getElementById('detail-catatan-revisi').innerText = data.catatanRevisi || data.catatan_revisi;
            catRow.classList.remove('hidden');
        } else {
            catRow.classList.add('hidden');
        }
    }

    const photosContainer = document.getElementById('admin-detail-photos-container');
    const emptyText = document.getElementById('admin-detail-photos-empty');

    if (photosContainer) {
        photosContainer.innerHTML = '';
        let photos = [];
        try {
            photos = JSON.parse(data.photos || '[]');
        } catch (e) {
            photos = [];
        }

        if (photos && photos.length > 0) {
            if (emptyText) emptyText.classList.add('hidden');
            photos.forEach(photo => {
                const imgCard = document.createElement('div');
                imgCard.className = 'relative group cursor-pointer overflow-hidden rounded-xl border border-gray-200 shadow-sm aspect-square bg-gray-100';
                imgCard.onclick = () => openLightbox(photo.url);
                imgCard.innerHTML = `
                    <img src="${photo.url}" alt="Foto Blank Spot" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold gap-1">
                        🔍 Perbesar
                    </div>
                `;
                photosContainer.appendChild(imgCard);
            });
        } else {
            if (emptyText) emptyText.classList.remove('hidden');
        }
    }

    setTimeout(() => {
        initMap(lat, lng);
    }, 200);

    document.getElementById('detailSection').scrollIntoView({ behavior: 'smooth' });
}

let detailMap;
function initMap(lat, lng) {
    if (detailMap) {
        detailMap.remove();
    }
    detailMap = L.map('validasiMap').setView([lat, lng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(detailMap);
    L.marker([lat, lng]).addTo(detailMap);
}
</script>

@endsection