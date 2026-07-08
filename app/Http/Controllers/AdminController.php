<?php

namespace App\Http\Controllers;

use App\Models\BlankSpot;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Show admin dashboard - PERBAIKAN (data dari database)
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        
        // ============================================================
        // STATISTIK DARI DATABASE
        // ============================================================
        $totalData = BlankSpot::count();
        $pendingCount = BlankSpot::where('status_validasi', 'pending')->count();
        $approvedCount = BlankSpot::where('status_validasi', 'approved')->count();
        $rejectedCount = BlankSpot::where('status_validasi', 'rejected')->count();
        
        // ============================================================
        // DATA TABEL - AMBIL SEMUA DATA DARI DATABASE
        // ============================================================
        $blankSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->orderBy('created_at', 'desc')
            ->get(); // <-- PERBAIKAN: ambil SEMUA data, bukan limit 10
        
        // ============================================================
        // DATA PENDING UNTUK VALIDASI
        // ============================================================
        $pendingSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('status_validasi', 'pending')
            ->orderBy('created_at', 'desc')
            ->get(); // <-- PERBAIKAN: ambil SEMUA pending
        
        // ============================================================
        // DATA UNTUK CHART (GRAFIK)
        // ============================================================
        $statusLabels = ['Pending', 'Approved', 'Rejected'];
        $statusCounts = [
            BlankSpot::where('status_validasi', 'pending')->count(),
            BlankSpot::where('status_validasi', 'approved')->count(),
            BlankSpot::where('status_validasi', 'rejected')->count()
        ];
        
        // Data per tahun untuk grafik
        $tahunData = BlankSpot::selectRaw('tahun, count(*) as total')
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get();
        
        $tahunLabels = $tahunData->pluck('tahun')->toArray();
        $tahunCounts = $tahunData->pluck('total')->toArray();
        
        // ============================================================
        // DATA UNTUK PETA
        // ============================================================
        $spotsPeta = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('status_validasi', 'approved')
            ->get();
        
        // ============================================================
        // DATA UNTUK FILTER
        // ============================================================
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        $tahunList = BlankSpot::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');
        
        // ============================================================
        // DATA VALIDASI MENUNGGU
        // ============================================================
        $totalMenunggu = BlankSpot::where('status_validasi', 'pending')->count();
        $totalDisetujui = BlankSpot::where('status_validasi', 'approved')->count();
        $totalDitolak = BlankSpot::where('status_validasi', 'rejected')->count();
        
        $status = $request->status ?? 'pending';

        $validasiMenunggu = BlankSpot::with([
            'kabupaten',
            'kecamatan',
            'desa',
            'creator'
        ])->orderBy('created_at', 'desc')
        ->get();
        
        // ============================================================
        // STATISTIK CARD
        // ============================================================
        $tahunStats = BlankSpot::selectRaw('YEAR(tahun) as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();
        
        $nilaiRataRata = $tahunStats->avg('total') ?? 0;
        
        $nilaiTertinggiData = BlankSpot::selectRaw('YEAR(tahun) as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('total', 'desc')
            ->first();
        
        $nilaiTerendahData = BlankSpot::selectRaw('YEAR(tahun) as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('total', 'asc')
            ->first();
        
        $nilaiTertinggi = $nilaiTertinggiData ? $nilaiTertinggiData->total : 0;
        $tahunTertinggi = $nilaiTertinggiData ? $nilaiTertinggiData->year : '-';
        $nilaiTerendah = $nilaiTerendahData ? $nilaiTerendahData->total : 0;
        $tahunTerendah = $nilaiTerendahData ? $nilaiTerendahData->year : '-';
        
        return view('admin.dashboard', compact(
            'totalData', 'pendingCount', 'approvedCount', 'rejectedCount',
            'blankSpots', 'pendingSpots',
            'statusLabels', 'statusCounts',
            'tahunLabels', 'tahunCounts',
            'spotsPeta', 'kabupatens', 'tahunList',
            'totalMenunggu', 'totalDisetujui', 'totalDitolak',
            'validasiMenunggu',
            'nilaiRataRata', 'nilaiTertinggi', 'tahunTertinggi',
            'nilaiTerendah', 'tahunTerendah'
        ));
    }

    /**
     * Halaman daftar kabupaten/kota (card view)
     */
    public function addPage()
    {
        $kabupatens = Kabupaten::withCount('blankSpots')
            ->orderBy('nama_kabupaten')
            ->get();

        return view('admin.add', compact('kabupatens'));
    }
    
    /**
     * Halaman detail per kabupaten
     */
    public function detailPage($kabupaten_id)
    {
        $kabupaten = Kabupaten::findOrFail($kabupaten_id);
        
        $blankSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('kabupaten_id', $kabupaten_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $kecamatans = Kecamatan::where('kabupaten_id', $kabupaten_id)
            ->orderBy('nama_kecamatan')
            ->get();

        return view('admin.detail', compact('kabupaten', 'blankSpots', 'kecamatans'));
    }

    /**
     * Halaman detail wilayah (untuk route /wilayah/{slug})
     */
    public function detailWilayah($slug)
    {
        $kabupaten = Kabupaten::where('nama_kabupaten', 'LIKE', '%' . str_replace('-', ' ', $slug) . '%')
            ->firstOrFail();
            
        return redirect()->route('admin.detail', $kabupaten->id);
    }
}