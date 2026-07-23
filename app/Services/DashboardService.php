<?php

namespace App\Services;

use App\Models\BlankSpot;
use App\Models\Kabupaten;
use App\Models\Desa;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Build statistics summary from database for Admin Dashboard
     */
    public function getAdminStats(): array
    {
        $totalData       = BlankSpot::where('status_validasi', 'approved')->count();
        $pendingCount    = BlankSpot::where('status_validasi', 'pending')->count();
        $approvedCount   = BlankSpot::where('status_validasi', 'approved')->count();
        $rejectedCount   = BlankSpot::where('status_validasi', 'rejected')->count();
        $revisiCount     = BlankSpot::whereIn('status_validasi', ['revisi', 'perlu_revisi'])->count();

        // Total Kabupaten Reporting
        $totalKabupatenReporting = BlankSpot::where('status_validasi', 'approved')
            ->distinct('kabupaten_id')
            ->count('kabupaten_id');

        // Total Desa Terdampak
        $totalDesaTerdampak = BlankSpot::where('status_validasi', 'approved')
            ->distinct('desa_id')
            ->count('desa_id');

        // Highest & Lowest Kabupaten by count
        $kabupatenStats = Kabupaten::withCount(['blankSpots' => function ($q) {
            $q->where('status_validasi', 'approved');
        }])->get();

        $highestKabupaten = $kabupatenStats->sortByDesc('blank_spots_count')->first();
        $lowestKabupaten  = $kabupatenStats->where('blank_spots_count', '>', 0)->sortBy('blank_spots_count')->first();

        // Network Status Statistics
        $networkStats = BlankSpot::where('status_validasi', 'approved')
            ->select('status_jaringan', DB::raw('count(*) as total'))
            ->groupBy('status_jaringan')
            ->pluck('total', 'status_jaringan')
            ->toArray();

        // Priority Statistics
        $priorityStats = BlankSpot::where('status_validasi', 'approved')
            ->select('prioritas', DB::raw('count(*) as total'))
            ->groupBy('prioritas')
            ->orderBy('prioritas')
            ->pluck('total', 'prioritas')
            ->toArray();

        // Year Statistics
        $yearStats = BlankSpot::where('status_validasi', 'approved')
            ->select('tahun', DB::raw('count(*) as total'))
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get();

        // Recent Submissions
        $recentSubmissions = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return [
            'totalData'               => $totalData,
            'pendingCount'            => $pendingCount,
            'approvedCount'           => $approvedCount,
            'rejectedCount'           => $rejectedCount,
            'revisiCount'             => $revisiCount,
            'totalKabupatenReporting' => $totalKabupatenReporting,
            'totalDesaTerdampak'      => $totalDesaTerdampak,
            'highestKabupaten'        => $highestKabupaten ? $highestKabupaten->nama_kabupaten . " ({$highestKabupaten->blank_spots_count})" : '-',
            'lowestKabupaten'         => $lowestKabupaten ? $lowestKabupaten->nama_kabupaten . " ({$lowestKabupaten->blank_spots_count})" : '-',
            'networkStats'            => $networkStats,
            'priorityStats'           => $priorityStats,
            'yearStats'               => $yearStats,
            'recentSubmissions'       => $recentSubmissions,
        ];
    }

    /**
     * Build statistics summary from database for Operator Dashboard
     */
    public function getOperatorStats(User $user): array
    {
        $kabupatenId = $user->kabupaten_id;

        $totalData     = BlankSpot::where('kabupaten_id', $kabupatenId)->where('status_validasi', 'approved')->count();
        $pendingCount  = BlankSpot::where('kabupaten_id', $kabupatenId)->where('status_validasi', 'pending')->count();
        $approvedCount = BlankSpot::where('kabupaten_id', $kabupatenId)->where('status_validasi', 'approved')->count();
        $rejectedCount = BlankSpot::where('kabupaten_id', $kabupatenId)->where('status_validasi', 'rejected')->count();
        $revisiCount   = BlankSpot::where('kabupaten_id', $kabupatenId)->whereIn('status_validasi', ['revisi', 'perlu_revisi'])->count();

        // Recent Submissions for Operator
        $recentSubmissions = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('kabupaten_id', $kabupatenId)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return [
            'totalData'         => $totalData,
            'pendingCount'      => $pendingCount,
            'approvedCount'     => $approvedCount,
            'rejectedCount'     => $rejectedCount,
            'revisiCount'       => $revisiCount,
            'recentSubmissions' => $recentSubmissions,
        ];
    }
}
