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

        // Tabel Dashboard (SEMUA DATA)
        $blankSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Statistik
        $totalData = BlankSpot::count();

        $pendingCount = BlankSpot::where('status_validasi', 'pending')->count();

        $approvedCount = BlankSpot::where('status_validasi', 'approved')->count();

        $rejectedCount = BlankSpot::where('status_validasi', 'rejected')->count();

        // Grafik
        $tahunData = BlankSpot::selectRaw('tahun, count(*) as total')
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get();

        $grafikLabels = $tahunData->pluck('tahun')->toArray();
        $grafikData = $tahunData->pluck('total')->toArray();

        // Peta
        $spotsPeta = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('status_validasi', 'approved')
            ->get();

        $kabupaten = Kabupaten::find($user->kabupaten_id);

        // List Tahun
        $tahunList = BlankSpot::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Statistik Tahunan
        $nilaiRataRata = BlankSpot::selectRaw('tahun, COUNT(*) as total')
            ->groupBy('tahun')
            ->get()
            ->avg('total') ?? 0;

        $nilaiTertinggiData = BlankSpot::selectRaw('tahun, COUNT(*) as total')
            ->groupBy('tahun')
            ->orderByDesc('total')
            ->first();

        $nilaiTerendahData = BlankSpot::selectRaw('tahun, COUNT(*) as total')
            ->groupBy('tahun')
            ->orderBy('total')
            ->first();

        $nilaiTertinggi = $nilaiTertinggiData?->total ?? 0;
        $tahunTertinggi = $nilaiTertinggiData?->tahun ?? '-';

        $nilaiTerendah = $nilaiTerendahData?->total ?? 0;
        $tahunTerendah = $nilaiTerendahData?->tahun ?? '-';

        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();

        return view('user.dashboard', compact(
            'blankSpots', 'totalData', 'pendingCount', 'approvedCount', 'rejectedCount',
            'grafikLabels', 'grafikData', 'spotsPeta', 'kabupaten',
            'tahunList', 'nilaiRataRata', 'nilaiTertinggi', 'tahunTertinggi',
            'nilaiTerendah', 'tahunTerendah', 'kabupatens'
        ));
    }

    /**
     * Halaman daftar kabupaten/kota untuk user (card view)
     * - Menampilkan SEMUA kabupaten
     * - Angka = total data milik user di kabupaten tersebut
     */
        public function addPage()
    {
        $user = Auth::user();

        $kabupatens = Kabupaten::withCount('blankSpots')
            ->orderBy('nama_kabupaten')
            ->get();

        $userKabupatenId = $user->kabupaten_id;

        return view('user.add', compact(
            'kabupatens',
            'userKabupatenId'
        ));
    }

    /**
     * Halaman detail per kabupaten untuk user
     * - Hanya menampilkan data milik user
     * - Hanya bisa tambah/edit/hapus di kabupaten sendiri
     */
        public function detailPage($kabupaten_id)
    {
        $user = Auth::user();

        $kabupaten = Kabupaten::findOrFail($kabupaten_id);

        // Semua data di kabupaten tersebut
        $blankSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('kabupaten_id', $kabupaten_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $kecamatans = Kecamatan::where('kabupaten_id', $kabupaten_id)
            ->orderBy('nama_kecamatan')
            ->get();

        // Apakah ini kabupaten milik user?
        $isOwner = ($user->kabupaten_id == $kabupaten_id);

        return view('user.detail', compact(
            'kabupaten',
            'blankSpots',
            'kecamatans',
            'isOwner'
        ));
    }
}