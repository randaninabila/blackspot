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

        // Ambil data statistik ringkasan dari DashboardService
        $stats = $this->dashboardService->getAdminStats();

        // Base Query utama untuk data approved yang ditampilkan pada dashboard tabel & card
        $baseQuery = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator', 'validator', 'photos'])
            ->where('status_validasi', 'approved');

        if ($request->filled('kabupaten_id')) {
            $baseQuery->where('kabupaten_id', $request->kabupaten_id);
        }
        if ($request->filled('tahun')) {
            $baseQuery->where('tahun', $request->tahun);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $baseQuery->where(function ($q) use ($search) {
                $q->whereHas('kabupaten', fn($sq) => $sq->where('nama_kabupaten', 'LIKE', "%{$search}%"))
                  ->orWhereHas('kecamatan', fn($sq) => $sq->where('nama_kecamatan', 'LIKE', "%{$search}%"))
                  ->orWhereHas('desa', fn($sq) => $sq->where('nama_desa', 'LIKE', "%{$search}%"))
                  ->orWhere('nama_lokasi', 'LIKE', "%{$search}%");
            });
        }

        // Bug 1 Fix: Card Total Data persis sama dengan jumlah query dasar tabel
        $totalData     = (clone $baseQuery)->count();
        $pendingCount  = BlankSpot::where('status_validasi', 'pending')->count();
        $approvedCount = $totalData;
        $rejectedCount = BlankSpot::where('status_validasi', 'rejected')->count();

        // Data tabel (Approved)
        $blankSpots = (clone $baseQuery)
            ->orderBy('created_at', 'desc')
            ->get();

        // Bug 2 Fix: Top Kabupaten dihitung dari base query yang sama
        $topKabupaten = (clone $baseQuery)
            ->select('kabupaten_id', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
            ->groupBy('kabupaten_id')
            ->orderByDesc('total')
            ->with('kabupaten')
            ->first();

        $topKabupatenName   = ($topKabupaten && $topKabupaten->kabupaten) ? $topKabupaten->kabupaten->nama_kabupaten : '-';
        $topKabupatenTotal  = $topKabupaten ? $topKabupaten->total : 0;
        $topKabupatenYear   = $request->filled('tahun') ? $request->tahun : ((clone $baseQuery)->where('kabupaten_id', $topKabupaten?->kabupaten_id)->max('tahun') ?? date('Y'));
        $kabupatenTerbanyak = $topKabupatenName;

        // Data pending untuk validasi
        $pendingSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator', 'validator', 'photos'])
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
        $tahunData = (clone $baseQuery)
            ->selectRaw('tahun, count(*) as total')
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get();

        $tahunLabels = $tahunData->pluck('tahun')->toArray();
        $tahunCounts = $tahunData->pluck('total')->toArray();

        // Data untuk peta
        $spotsPeta = (clone $baseQuery)->get();

        // Data untuk filter
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        $tahunList  = BlankSpot::where('status_validasi', 'approved')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Data validasi
        $totalMenunggu  = $pendingCount;
        $totalDisetujui = $approvedCount;
        $totalDitolak   = $rejectedCount;

        // Dynamic query untuk data validasi
        $vmQuery = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator', 'validator', 'photos']);

        if ($request->filled('kabupaten_id')) {
            $vmQuery->where('kabupaten_id', $request->kabupaten_id);
        }

        if ($request->filled('status_validasi')) {
            $vmQuery->where('status_validasi', $request->status_validasi);
        } elseif ($request->filled('status') && $request->status !== 'all') {
            $vmQuery->where('status_validasi', $request->status);
        }

        if ($request->filled('tahun')) {
            $vmQuery->where('tahun', $request->tahun);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $vmQuery->where(function ($q) use ($search) {
                $q->whereHas('kabupaten', fn($sq) => $sq->where('nama_kabupaten', 'LIKE', "%{$search}%"))
                  ->orWhereHas('kecamatan', fn($sq) => $sq->where('nama_kecamatan', 'LIKE', "%{$search}%"))
                  ->orWhereHas('desa', fn($sq) => $sq->where('nama_desa', 'LIKE', "%{$search}%"))
                  ->orWhere('nama_lokasi', 'LIKE', "%{$search}%");
            });
        }

        $validasiMenunggu = $vmQuery->orderBy('created_at', 'desc')->get();

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
            'stats', 'totalData', 'pendingCount', 'approvedCount', 'rejectedCount',
            'blankSpots', 'pendingSpots',
            'statusLabels', 'statusCounts',
            'tahunLabels', 'tahunCounts',
            'spotsPeta', 'kabupatens', 'tahunList',
            'totalMenunggu', 'totalDisetujui', 'totalDitolak',
            'validasiMenunggu',
            'nilaiRataRata', 'nilaiTertinggi', 'tahunTertinggi',
            'nilaiTerendah', 'tahunTerendah',
            'topKabupatenName', 'topKabupatenTotal', 'topKabupatenYear', 'kabupatenTerbanyak'
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

        $blankSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator', 'validator', 'photos'])
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