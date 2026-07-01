@extends('app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-[#234B26]">Data Blank Spot</h1>
        <a href="{{ route('admin.blank-spot.create') }}"
            class="bg-[#234B26] text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-[#1a381c] transition">
            + Tambah Data
        </a>
    </div>

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

    <!-- Filter -->
    <form method="GET" action="{{ route('admin.blank-spot.index') }}"
          class="bg-[#F3F3E8] rounded-2xl p-6 mb-8 flex flex-wrap gap-4 items-end">
       <div>
    <label class="block text-[#234B26] font-bold text-sm mb-1">Tahun</label>
    <select name="tahun"
        class="bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm outline-none min-w-[120px] appearance-none focus:border-[#234B26] transition-all"
        style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; padding-right: 36px;">
        <option value="">-- Semua --</option>
        <option value="2025">2025</option>
        <option value="2026">2026</option>
    </select>
</div>
        <div>
            <label class="block text-[#234B26] font-bold text-sm mb-1">Status Validasi</label>
            <select name="status_validasi"
                class="bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm outline-none min-w-[150px] appearance-none focus:border-[#234B26] transition-all"
                style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; padding-right: 36px;">
                <option value="">-- Semua --</option>
                <option value="pending"   {{ request('status_validasi') == 'pending'   ? 'selected' : '' }}>Menunggu</option>
                <option value="approved"  {{ request('status_validasi') == 'approved'  ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected"  {{ request('status_validasi') == 'rejected'  ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div>
            <label class="block text-[#234B26] font-bold text-sm mb-1">Tahun</label>
            <select name="tahun"
                class="bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm outline-none min-w-[120px] appearance-none focus:border-[#234B26] transition-all"
                style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; padding-right: 36px;">
                <option value="">-- Semua --</option>
                @foreach($tahuns as $t)
                    <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[#234B26] font-bold text-sm mb-1">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Kabupaten, kecamatan, desa..."
                class="w-full bg-white border border-[#234B26]/30 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#234B26] transition-all">
        </div>
        <button type="submit"
            class="bg-[#234B26] text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-[#1a381c] transition">
            Filter
        </button>
        <a href="{{ route('admin.blank-spot.index') }}"
            class="border border-[#234B26] text-[#234B26] px-6 py-2.5 rounded-xl font-semibold hover:bg-[#D7E3D4] transition">
            Reset
        </a>
    </form>

    <!-- Tabel -->
    <div class="bg-[#F3F3E8] rounded-3xl shadow-xl p-8 overflow-x-auto">
        <table class="w-full text-sm text-left text-[#234B26]">
            <thead class="border-b-2 border-[#234B26] bg-[#D7E3D4]">
                <tr>
                    <th class="px-4 py-3 text-center font-bold">No</th>
                    <th class="px-4 py-3 font-bold">Kabupaten/Kota</th>
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
                <tr class="border-b border-gray-200 hover:bg-[#F3F3E8]/50 transition">
                    <td class="px-4 py-3 text-center">{{ $blankSpots->firstItem() + $i }}</td>
                    <td class="px-4 py-3">{{ $spot->kabupaten->nama_kabupaten ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $spot->kecamatan->nama_kecamatan ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $spot->desa->nama_desa ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $spot->latitude }}</td>
                    <td class="px-4 py-3">{{ $spot->longitude }}</td>
                    <td class="px-4 py-3 text-center">{{ $spot->tahun }}</td>
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold 
                            {{ $spot->status_validasi == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $spot->status_validasi == 'approved' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $spot->status_validasi == 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($spot->status_validasi) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex justify-center gap-2">
                            <!-- EDIT -->
                            <a href="{{ route('admin.blank-spot.edit', $spot->id) }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.25 2.25 0 113.182 3.182L7.5 20.213 3 21l.787-4.5L16.862 4.487z" />
                                </svg>
                            </a>

                            <!-- HAPUS -->
                            <form action="{{ route('admin.blank-spot.destroy', $spot->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M10 11v6M14 11v6M5 7l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-8 text-gray-400">
                        Tidak ada data yang ditemukan
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
</div>

<style>
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

@endsection