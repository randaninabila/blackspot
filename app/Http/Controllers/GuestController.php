<?php

namespace App\Http\Controllers;

use App\Models\BlankSpot;
use App\Models\Kabupaten;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{
    /**
     * Menampilkan halaman utama (Guest Landing Page) dengan data realtime dari database.
     */
    public function index()
    {
        if (auth()->check()) {
            return auth()->user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');
        }

        // Base query untuk data approved
        $baseQuery = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator', 'validator', 'photos'])
            ->where('status_validasi', 'approved');

        // Bug 1 Fix: Card Total Data
        $totalData = (clone $baseQuery)->count();

        // Bug 2 Fix: Kabupaten dengan area blankspot terbanyak dihitung dinamis dari baseQuery
        $topKabupaten = (clone $baseQuery)
            ->select('kabupaten_id', DB::raw('count(*) as total'))
            ->groupBy('kabupaten_id')
            ->orderByDesc('total')
            ->with('kabupaten')
            ->first();

        $topKabupatenName   = ($topKabupaten && $topKabupaten->kabupaten) ? $topKabupaten->kabupaten->nama_kabupaten : '-';
        $topKabupatenTotal  = $topKabupaten ? $topKabupaten->total : 0;
        $topKabupatenYear   = (clone $baseQuery)->where('kabupaten_id', $topKabupaten?->kabupaten_id)->max('tahun') ?? date('Y');
        $kabupatenTerbanyak = $topKabupatenName;

        // Data tabel
        $blankSpots = (clone $baseQuery)
            ->orderBy('created_at', 'desc')
            ->get();

        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        $spots = $blankSpots;
        $stats = app(DashboardService::class)->getAdminStats();

        // Bar Chart Data (Only approved data by Kabupaten)
        $kabupatenCounts = (clone $baseQuery)
            ->select('kabupaten_id', DB::raw('count(*) as total'))
            ->groupBy('kabupaten_id')
            ->pluck('total', 'kabupaten_id')
            ->toArray();

        $chartLabels = [];
        $chartValues = [];

        foreach ($kabupatens as $kab) {
            $chartLabels[] = $kab->nama_kabupaten;
            $chartValues[] = $kabupatenCounts[$kab->id] ?? 0;
        }

        // Pie Chart Data (Only approved data by status_jaringan)
        $networkCounts = (clone $baseQuery)
            ->whereNotNull('status_jaringan')
            ->select('status_jaringan', DB::raw('count(*) as total'))
            ->groupBy('status_jaringan')
            ->pluck('total', 'status_jaringan')
            ->toArray();

        $defaultCategories = [
            "Zero Blankspot",
            "Sinyal Sangat Lemah",
            "Sinyal Lemah",
            "2G",
            "3G",
            "4G Tidak Stabil"
        ];

        $pieCategories = array_unique(array_merge($defaultCategories, array_keys($networkCounts)));
        $pieLabels = [];
        $pieValues = [];

        foreach ($pieCategories as $cat) {
            $count = $networkCounts[$cat] ?? 0;
            $percentage = $totalData > 0 ? round(($count / $totalData) * 100, 1) : 0;
            $pieLabels[] = $cat;
            $pieValues[] = $percentage;
        }

        // Geospatial Map Data (Only approved data)
        $spotsMapData = $blankSpots->map(function ($s) {
            return [
                'latitude'     => (float) $s->latitude,
                'longitude'    => (float) $s->longitude,
                'tahun'        => (int) $s->tahun,
                'kabupaten_id' => $s->kabupaten_id,
                'kecamatan'    => [
                    'nama_kecamatan' => $s->kecamatan->nama_kecamatan ?? '-'
                ],
                'desa'         => [
                    'nama_desa' => $s->desa->nama_desa ?? '-'
                ],
            ];
        });

        return view('guest', compact(
            'totalData',
            'topKabupatenName',
            'topKabupatenTotal',
            'topKabupatenYear',
            'kabupatenTerbanyak',
            'blankSpots',
            'kabupatens',
            'spots',
            'stats',
            'chartLabels',
            'chartValues',
            'pieLabels',
            'pieValues',
            'spotsMapData'
        ));
    }
}
