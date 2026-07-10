<?php

namespace App\Http\Controllers;

use App\Models\BlankSpot;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * User dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Data tabel - HANYA data milik user
        $blankSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            // ->where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistik - HANYA data milik user
        $totalData = BlankSpot::where('created_by', $user->id)->count();
        $pendingCount = BlankSpot::where('created_by', $user->id)->where('status_validasi', 'pending')->count();
        $approvedCount = BlankSpot::where('created_by', $user->id)->where('status_validasi', 'approved')->count();
        $rejectedCount = BlankSpot::where('created_by', $user->id)->where('status_validasi', 'rejected')->count();

        // Grafik - HANYA data milik user
        $tahunData = BlankSpot::where('created_by', $user->id)
            ->selectRaw('tahun, count(*) as total')
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get();

        $grafikLabels = $tahunData->pluck('tahun')->toArray();
        $grafikData = $tahunData->pluck('total')->toArray();

        // Peta - HANYA data milik user yang sudah approved
        // Ini adalah dataset AWAL yang tampil saat halaman pertama kali dibuka
        // (sebelum user menekan tombol Pratinjau). Setelah difilter, JS akan
        // mengganti isi peta dengan hasil dari endpoint /user/api/filter-geospasial.
        $spotsPeta = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            // ->where('created_by', $user->id)
            ->where('status_validasi', 'approved')
            ->get();

        $kabupaten = Kabupaten::find($user->kabupaten_id);

        // List Tahun dari data user - dipakai untuk isi dropdown "Tahun" di tab Geospasial
        // PENTING: nama variabel HARUS "tahuns" karena itu yang dipakai di dashboard.blade.php
        // (sebelumnya bernama "tahunList" sehingga tidak pernah sampai ke view dan dropdown kosong)
        $tahuns = BlankSpot::where('created_by', $user->id)
            ->where('status_validasi', 'approved')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Statistik tahunan user
        $nilaiRataRata = BlankSpot::where('created_by', $user->id)
            ->selectRaw('tahun, COUNT(*) as total')
            ->groupBy('tahun')
            ->get()
            ->avg('total') ?? 0;

        $nilaiTertinggiData = BlankSpot::where('created_by', $user->id)
            ->selectRaw('tahun, COUNT(*) as total')
            ->groupBy('tahun')
            ->orderByDesc('total')
            ->first();

        $nilaiTerendahData = BlankSpot::where('created_by', $user->id)
            ->selectRaw('tahun, COUNT(*) as total')
            ->groupBy('tahun')
            ->orderBy('total')
            ->first();

        $nilaiTertinggi = $nilaiTertinggiData?->total ?? 0;
        $tahunTertinggi = $nilaiTertinggiData?->tahun ?? '-';
        $nilaiTerendah = $nilaiTerendahData?->total ?? 0;
        $tahunTerendah = $nilaiTerendahData?->tahun ?? '-';

        // Kabupaten untuk filter
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();

        return view('user.dashboard', compact(
            'blankSpots', 'totalData', 'pendingCount', 'approvedCount', 'rejectedCount',
            'grafikLabels', 'grafikData', 'spotsPeta', 'kabupaten',
            'tahuns', 'nilaiRataRata', 'nilaiTertinggi', 'tahunTertinggi',
            'nilaiTerendah', 'tahunTerendah', 'kabupatens'
        ));
    }

    /**
     * Halaman daftar kabupaten/kota untuk user (card view)
     */
    public function addPage()
{
    $user = Auth::user();

    $kabupatens = Kabupaten::withCount([
        'blankSpots'
    ])->orderBy('nama_kabupaten')
      ->get();

    $userKabupatenId = $user->kabupaten_id;

    return view('user.add', compact('kabupatens', 'userKabupatenId'));
}

    /**
     * Halaman detail per kabupaten untuk user
     */
    public function detailPage($kabupaten_id)
    {
        $user = Auth::user();

        $kabupaten = Kabupaten::findOrFail($kabupaten_id);

        // Hanya data milik user di kabupaten tersebut
        $blankSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('kabupaten_id', $kabupaten_id)
            // ->where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $kecamatans = Kecamatan::where('kabupaten_id', $kabupaten_id)
            ->orderBy('nama_kecamatan')
            ->get();

        $isOwner = ($user->kabupaten_id == $kabupaten_id);

        return view('user.detail', compact('kabupaten', 'blankSpots', 'kecamatans', 'isOwner'));
    }

    /**
     * API Filter Geospasial untuk User
     *
     * Endpoint ini dipanggil oleh tombol "Pratinjau" di tab Geospasial dashboard user.
     * Logika filter di method ini sudah benar sejak awal:
     * - hanya data milik user yang sedang login (created_by)
     * - hanya status_validasi = 'approved'
     * - opsional difilter oleh kabupaten_id dan tahun
     *
     * Data JSON yang dikembalikan di sini SUDAH benar; masalah sebelumnya ada di sisi
     * JavaScript (renderMarkers) yang tidak memakai hasil fetch ini untuk menggambar ulang
     * peta. Setelah renderMarkers() diperbaiki agar menerima parameter, endpoint ini akan
     * langsung berfungsi tanpa perubahan tambahan.
     */
    public function filterGeospasial(Request $request)
    {
        $user = Auth::user();

        // Query hanya untuk data user yang sudah approved
        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('created_by', $user->id)
            ->where('status_validasi', 'approved');

        // Filter berdasarkan kabupaten
        if ($request->has('kabupaten_id') && $request->kabupaten_id != '' && $request->kabupaten_id != 'all') {
            $query->where('kabupaten_id', $request->kabupaten_id);
        }

        // Filter berdasarkan tahun
        if ($request->has('tahun') && $request->tahun != '') {
            $query->where('tahun', $request->tahun);
        }

        $spots = $query->get();

        $formattedSpots = $spots->map(function ($spot) {
            return [
                'id' => $spot->id,
                'lat' => (float) $spot->latitude,
                'lng' => (float) $spot->longitude,
                'kabupaten' => $spot->kabupaten->nama_kabupaten ?? '-',
                'kecamatan' => $spot->kecamatan->nama_kecamatan ?? '-',
                'desa' => $spot->desa->nama_desa ?? '-',
                'tahun' => $spot->tahun,
                'keterangan' => $spot->keterangan ?? 'Blank Spot',
                'kabupaten_id' => $spot->kabupaten_id,
                'status' => $spot->status_validasi,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedSpots,
            'total' => $formattedSpots->count()
        ]);
    }
}