<?php

namespace App\Http\Controllers;

use App\Models\BlankSpot;
use App\Models\Kabupaten;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class PublicApiController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Endpoint Statistik Publik (Tanpa Login)
     */
    public function stats()
    {
        $stats = $this->dashboardService->getAdminStats();

        return response()->json([
            'success' => true,
            'data'    => $stats,
        ]);
    }

    /**
     * Endpoint Data Blank Spot Publik (Tanpa Login)
     */
    public function blankSpots(Request $request)
    {
        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('status_validasi', 'approved');

        if ($request->filled('kabupaten_id')) {
            $query->where('kabupaten_id', $request->kabupaten_id);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        $data = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Endpoint Peta Geospasial Publik (Tanpa Login)
     */
    public function peta(Request $request)
    {
        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('status_validasi', 'approved');

        if ($request->filled('kabupaten_id')) {
            $query->where('kabupaten_id', $request->kabupaten_id);
        }

        $spots = $query->get()->map(function ($spot) {
            return [
                'id'              => $spot->id,
                'latitude'        => (float) $spot->latitude,
                'longitude'       => (float) $spot->longitude,
                'lat'             => (float) $spot->latitude,
                'lng'             => (float) $spot->longitude,
                'radius'          => (float) ($spot->radius ?? 0),
                'nama_lokasi'     => $spot->nama_lokasi ?? ($spot->desa->nama_desa ?? '-'),
                'kabupaten'       => $spot->kabupaten->nama_kabupaten ?? '-',
                'kecamatan'       => $spot->kecamatan->nama_kecamatan ?? '-',
                'desa'            => $spot->desa->nama_desa ?? '-',
                'prioritas'       => $spot->prioritas ? 'P' . $spot->prioritas : '-',
                'status_jaringan' => $spot->status_jaringan ?? 'Blank Spot',
                'tahun'           => $spot->tahun,
                'semester'        => $spot->semester ?? 1,
                'marker_color'    => $this->getMarkerColor($spot->prioritas, $spot->status_jaringan),
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $spots,
            'total'   => $spots->count(),
        ]);
    }

    /**
     * Endpoint Summary Dashboard Publik
     */
    public function dashboard()
    {
        $stats = $this->dashboardService->getAdminStats();

        return response()->json([
            'success' => true,
            'summary' => [
                'total_blank_spot' => $stats['totalData'],
                'total_kabupaten'  => $stats['totalKabupatenReporting'],
                'total_desa'       => $stats['totalDesaTerdampak'],
                'terbanyak'        => $stats['highestKabupaten'],
                'tersedikit'       => $stats['lowestKabupaten'],
            ],
        ]);
    }

    private function getMarkerColor(?int $prioritas, ?string $statusJaringan): string
    {
        if ($prioritas >= 1 && $prioritas <= 3) {
            return '#dc2626'; // Merah pekat
        } elseif ($prioritas >= 4 && $prioritas <= 6) {
            return '#f97316'; // Oranye
        } elseif ($prioritas >= 7 && $prioritas <= 10) {
            return '#eab308'; // Kuning
        }

        if ($statusJaringan) {
            $lower = strtolower($statusJaringan);
            if (str_contains($lower, 'blank') || str_contains($lower, 'tidak ada')) {
                return '#ef4444';
            } elseif (str_contains($lower, 'lemah') || str_contains($lower, 'tidak stabil')) {
                return '#f59e0b';
            } elseif (str_contains($lower, 'normal') || str_contains($lower, 'stabil')) {
                return '#22c55e';
            }
        }

        return '#ef4444';
    }
}
