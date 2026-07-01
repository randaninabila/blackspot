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
        
        $blankSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $totalData = BlankSpot::where('created_by', $user->id)->count();
        $pendingCount = BlankSpot::where('created_by', $user->id)
            ->where('status_validasi', 'pending')
            ->count();
        $approvedCount = BlankSpot::where('created_by', $user->id)
            ->where('status_validasi', 'approved')
            ->count();
        $rejectedCount = BlankSpot::where('created_by', $user->id)
            ->where('status_validasi', 'rejected')
            ->count();

        $tahunData = BlankSpot::where('created_by', $user->id)
            ->selectRaw('tahun, count(*) as total')
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get();

        $grafikLabels = $tahunData->pluck('tahun')->toArray();
        $grafikData = $tahunData->pluck('total')->toArray();

        $spotsPeta = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('created_by', $user->id)
            ->where('status_validasi', 'approved')
            ->get();

        $kabupaten = Kabupaten::find($user->kabupaten_id);
        $tahunList = BlankSpot::where('created_by', $user->id)
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $nilaiRataRata = BlankSpot::where('created_by', $user->id)
            ->selectRaw('YEAR(tahun) as year, COUNT(*) as total')
            ->groupBy('year')
            ->get()
            ->avg('total') ?? 0;

        $nilaiTertinggiData = BlankSpot::where('created_by', $user->id)
            ->selectRaw('YEAR(tahun) as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('total', 'desc')
            ->first();

        $nilaiTerendahData = BlankSpot::where('created_by', $user->id)
            ->selectRaw('YEAR(tahun) as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('total', 'asc')
            ->first();

        $nilaiTertinggi = $nilaiTertinggiData ? $nilaiTertinggiData->total : 0;
        $tahunTertinggi = $nilaiTertinggiData ? $nilaiTertinggiData->year : '-';
        $nilaiTerendah = $nilaiTerendahData ? $nilaiTerendahData->total : 0;
        $tahunTerendah = $nilaiTerendahData ? $nilaiTerendahData->year : '-';

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
        
        // Ambil SEMUA kabupaten dengan total blank spot (HANYA milik user ini)
        $kabupatens = Kabupaten::withCount(['blankSpots' => function($query) use ($user) {
            $query->where('created_by', $user->id);
        }])->orderBy('nama_kabupaten')->get();

        return view('user.add', compact('kabupatens'));
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
        
        // Hanya tampilkan data milik user ini
        $blankSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('kabupaten_id', $kabupaten_id)
            ->where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $kecamatans = Kecamatan::where('kabupaten_id', $kabupaten_id)
            ->orderBy('nama_kecamatan')
            ->get();

        // Cek apakah ini kabupaten milik user (bisa edit/hapus/tambah)
        $isOwner = ($user->kabupaten_id == $kabupaten_id);

        return view('user.detail', compact('kabupaten', 'blankSpots', 'kecamatans', 'isOwner'));
    }
}