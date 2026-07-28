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

        $totalData = BlankSpot::where('status_validasi', 'approved')->count();

        // Kabupaten dengan area blankspot terbanyak
        $topKabupaten = BlankSpot::where('status_validasi', 'approved')
            ->select('kabupaten_id', DB::raw('count(*) as total'))
            ->groupBy('kabupaten_id')
            ->orderBy('total', 'desc')
            ->first();

        $kabupatenTerbanyak = ($topKabupaten && $topKabupaten->kabupaten)
            ? $topKabupaten->kabupaten->nama_kabupaten
            : 'Kab Johor';

        $blankSpots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator', 'validator', 'photos'])
            ->where('status_validasi', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        $spots = $blankSpots;
        $stats = app(DashboardService::class)->getAdminStats();

        return view('guest', compact(
            'totalData',
            'kabupatenTerbanyak',
            'blankSpots',
            'kabupatens',
            'spots',
            'stats'
        ));
    }
}
