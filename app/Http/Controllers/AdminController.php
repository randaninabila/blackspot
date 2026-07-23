<?php

namespace App\Http\Controllers;

use App\Models\BlankSpot;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Show admin dashboard (Data riil dari database)
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // Ambil data statistik dari DashboardService
        $stats = $this->dashboardService->getAdminStats();

        $totalData     = $stats['totalData'];
        $pendingCount  = $stats['pendingCount'];
        $approvedCount = $stats['approvedCount'];
        $rejectedCount = $stats['rejectedCount'];

        // Data tabel - AMBIL SEMUA DATA APPROVED DARI DATABASE
        $blankSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('status_validasi', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        // Data pending untuk validasi
        $pendingSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('status_validasi', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Data untuk grafik
        $statusLabels = ['Pending', 'Approved', 'Rejected', 'Perlu Revisi'];
        $statusCounts = [
            $pendingCount,
            $approvedCount,
            $rejectedCount,
            $stats['revisiCount'],
        ];

        // Data per tahun untuk grafik
        $tahunData = BlankSpot::where('status_validasi', 'approved')
            ->selectRaw('tahun, count(*) as total')
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get();

        $tahunLabels = $tahunData->pluck('tahun')->toArray();
        $tahunCounts = $tahunData->pluck('total')->toArray();

        // Data untuk peta
        $spotsPeta = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('status_validasi', 'approved')
            ->get();

        // Data untuk filter
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        $tahunList  = BlankSpot::where('status_validasi', 'approved')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Data validasi menunggu
        $totalMenunggu  = $pendingCount;
        $totalDisetujui = $approvedCount;
        $totalDitolak   = $rejectedCount;

        $status = $request->status ?? 'pending';

        $validasiMenunggu = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistik card
        $tahunStats = BlankSpot::where('status_validasi', 'approved')
            ->selectRaw('tahun as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        $nilaiRataRata = $tahunStats->avg('total') ?? 0;

        $nilaiTertinggiData = BlankSpot::where('status_validasi', 'approved')
            ->selectRaw('tahun as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('total', 'desc')
            ->first();

        $nilaiTerendahData = BlankSpot::where('status_validasi', 'approved')
            ->selectRaw('tahun as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('total', 'asc')
            ->first();

        $nilaiTertinggi = $nilaiTertinggiData ? $nilaiTertinggiData->total : 0;
        $tahunTertinggi = $nilaiTertinggiData ? $nilaiTertinggiData->year : '-';
        $nilaiTerendah  = $nilaiTerendahData ? $nilaiTerendahData->total : 0;
        $tahunTerendah  = $nilaiTerendahData ? $nilaiTerendahData->year : '-';

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
        $kabupatens = Kabupaten::withCount([
            'blankSpots' => function ($query) {
                $query->where('status_validasi', 'approved');
            }
        ])
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