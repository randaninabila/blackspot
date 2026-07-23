<?php

namespace App\Http\Controllers;

use App\Models\BlankSpot;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * User dashboard (Operator Kabupaten)
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Data tabel - HANYA data milik kabupaten user
        $blankSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('kabupaten_id', $user->kabupaten_id)
            ->where('status_validasi', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistik - HANYA data milik kabupaten user
        $totalData     = BlankSpot::where('kabupaten_id', $user->kabupaten_id)->where('status_validasi', 'approved')->count();
        $pendingCount  = BlankSpot::where('kabupaten_id', $user->kabupaten_id)->where('status_validasi', 'pending')->count();
        $approvedCount = BlankSpot::where('kabupaten_id', $user->kabupaten_id)->where('status_validasi', 'approved')->count();
        $rejectedCount = BlankSpot::where('kabupaten_id', $user->kabupaten_id)->where('status_validasi', 'rejected')->count();

        // Grafik - HANYA data milik kabupaten user
        $tahunData = BlankSpot::where('kabupaten_id', $user->kabupaten_id)
            ->selectRaw('tahun, count(*) as total')
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get();

        $grafikLabels = $tahunData->pluck('tahun')->toArray();
        $grafikData   = $tahunData->pluck('total')->toArray();

        // Peta - HANYA data milik kabupaten user yang sudah approved
        $spotsPeta = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('kabupaten_id', $user->kabupaten_id)
            ->where('status_validasi', 'approved')
            ->get();

        $kabupaten = Kabupaten::find($user->kabupaten_id);

        // List Tahun dari data kabupaten user
        $tahuns = BlankSpot::where('kabupaten_id', $user->kabupaten_id)
            ->where('status_validasi', 'approved')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Statistik tahunan user
        $nilaiRataRata = BlankSpot::where('kabupaten_id', $user->kabupaten_id)
            ->where('status_validasi', 'approved')
            ->selectRaw('tahun, COUNT(*) as total')
            ->groupBy('tahun')
            ->get()
            ->avg('total') ?? 0;

        $nilaiTertinggiData = BlankSpot::where('kabupaten_id', $user->kabupaten_id)
            ->where('status_validasi', 'approved')
            ->selectRaw('tahun, COUNT(*) as total')
            ->groupBy('tahun')
            ->orderByDesc('total')
            ->first();

        $nilaiTerendahData = BlankSpot::where('kabupaten_id', $user->kabupaten_id)
            ->where('status_validasi', 'approved')
            ->selectRaw('tahun, COUNT(*) as total')
            ->groupBy('tahun')
            ->orderBy('total')
            ->first();

        $nilaiTertinggi = $nilaiTertinggiData?->total ?? 0;
        $tahunTertinggi = $nilaiTertinggiData?->tahun ?? '-';
        $nilaiTerendah  = $nilaiTerendahData?->total ?? 0;
        $tahunTerendah  = $nilaiTerendahData?->tahun ?? '-';

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
            'blankSpots' => function ($query) {
                $query->where('status_validasi', 'approved');
            }
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

        $blankSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('kabupaten_id', $kabupaten_id)
            ->where('status_validasi', 'approved')
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
     */
    public function filterGeospasial(Request $request)
    {
        $user = Auth::user();

        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('kabupaten_id', $user->kabupaten_id)
            ->where('status_validasi', 'approved');

        if ($request->filled('kabupaten_id') && $request->kabupaten_id !== 'all') {
            $query->where('kabupaten_id', $request->kabupaten_id);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $spots = $query->get();

        $formattedSpots = $spots->map(function ($spot) {
            return [
                'id'              => $spot->id,
                'lat'             => (float) $spot->latitude,
                'lng'             => (float) $spot->longitude,
                'radius'          => (float) ($spot->radius ?? 0),
                'prioritas'       => $spot->prioritas ?? '-',
                'status_jaringan' => $spot->status_jaringan ?? '-',
                'nama_lokasi'     => $spot->nama_lokasi ?? '-',
                'kabupaten'       => $spot->kabupaten->nama_kabupaten ?? '-',
                'kecamatan'       => $spot->kecamatan->nama_kecamatan ?? '-',
                'desa'            => $spot->desa->nama_desa ?? '-',
                'tahun'           => $spot->tahun,
                'keterangan'      => $spot->keterangan ?? 'Blank Spot',
                'kabupaten_id'    => $spot->kabupaten_id,
                'status'          => $spot->status_validasi,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $formattedSpots,
            'total'   => $formattedSpots->count()
        ]);
    }
}