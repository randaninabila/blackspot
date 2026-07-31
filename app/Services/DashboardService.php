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
        $baseQuery            = BlankSpot::where('status_validasi', 'approved');

        $totalData            = (clone $baseQuery)->count();
        $pendingCount         = BlankSpot::where('status_validasi', 'pending')->count();
        $diverifikasiCount    = BlankSpot::where('status_validasi', 'sedang_diverifikasi')->count();
        $approvedCount        = (clone $baseQuery)->count();
        $rejectedCount        = BlankSpot::where('status_validasi', 'rejected')->count();
        $revisiCount          = BlankSpot::whereIn('status_validasi', ['revisi', 'perlu_revisi'])->count();

        // Total Kabupaten Reporting
        $totalKabupatenReporting = (clone $baseQuery)
            ->distinct('kabupaten_id')
            ->count('kabupaten_id');

        // Total Desa Terdampak
        $totalDesaTerdampak = (clone $baseQuery)
            ->distinct('desa_id')
            ->count('desa_id');

        // Highest & Lowest Kabupaten by count
        $kabupatenStats = Kabupaten::withCount(['blankSpots' => function ($q) {
            $q->where('status_validasi', 'approved');
        }])->get();

        $highestKabupaten = $kabupatenStats->sortByDesc('blank_spots_count')->first();
        $lowestKabupaten  = $kabupatenStats->where('blank_spots_count', '>', 0)->sortBy('blank_spots_count')->first();

        // Kabupaten Bar Chart Data (Only approved)
        $kabupatenBarStats = $kabupatenStats->map(function ($k) {
            return [
                'nama' => $k->nama_kabupaten,
                'total' => $k->blank_spots_count,
            ];
        })->toArray();

        // Pie Chart: Network Status Statistics (Only approved)
        $networkStats = BlankSpot::where('status_validasi', 'approved')
            ->select('status_jaringan', DB::raw('count(*) as total'))
            ->groupBy('status_jaringan')
            ->pluck('total', 'status_jaringan')
            ->toArray();

        // Bar Chart: Kondisi Geografis Statistics (Only approved)
        $geografisStats = BlankSpot::where('status_validasi', 'approved')
            ->whereNotNull('kondisi_geografis')
            ->select('kondisi_geografis', DB::raw('count(*) as total'))
            ->groupBy('kondisi_geografis')
            ->pluck('total', 'kondisi_geografis')
            ->toArray();

        // Bar Chart: Status Validasi Statistics
        $statusValidasiStats = [
            'Pending'      => $pendingCount,
            'Disetujui'    => $approvedCount,
            'Ditolak'      => $rejectedCount,
            'Perlu Revisi' => $revisiCount,
        ];

        // Priority Statistics (P1-P10)
        $priorityStats = BlankSpot::where('status_validasi', 'approved')
            ->select('prioritas', DB::raw('count(*) as total'))
            ->whereNotNull('prioritas')
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

        // Semester Statistics
        $semesterStats = BlankSpot::where('status_validasi', 'approved')
            ->select('semester', DB::raw('count(*) as total'))
            ->whereNotNull('semester')
            ->groupBy('semester')
            ->orderBy('semester', 'asc')
            ->pluck('total', 'semester')
            ->toArray();

        // Recent Submissions
        $recentSubmissions = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return [
            'totalData'               => $totalData,
            'pendingCount'            => $pendingCount,
            'diverifikasiCount'       => $diverifikasiCount,
            'approvedCount'           => $approvedCount,
            'rejectedCount'           => $rejectedCount,
            'revisiCount'             => $revisiCount,
            'totalKabupatenReporting' => $totalKabupatenReporting,
            'totalDesaTerdampak'      => $totalDesaTerdampak,
            'highestKabupaten'        => $highestKabupaten ? $highestKabupaten->nama_kabupaten . " ({$highestKabupaten->blank_spots_count})" : '-',
            'lowestKabupaten'         => $lowestKabupaten ? $lowestKabupaten->nama_kabupaten . " ({$lowestKabupaten->blank_spots_count})" : '-',
            'networkStats'            => $networkStats,
            'geografisStats'          => $geografisStats,
            'statusValidasiStats'     => $statusValidasiStats,
            'kabupatenBarStats'       => $kabupatenBarStats,
            'priorityStats'           => $priorityStats,
            'yearStats'               => $yearStats,
            'semesterStats'           => $semesterStats,
            'recentSubmissions'       => $recentSubmissions,
        ];
    }

    /**
     * Build statistics summary from database for Operator Dashboard
     */
    public function getOperatorStats(User $user): array
    {
        $userId      = $user->id;
        $kabupatenId = $user->kabupaten_id;

        $baseQuery   = BlankSpot::where('kabupaten_id', $kabupatenId)->where('status_validasi', 'approved');

        $totalData         = (clone $baseQuery)->count();
        $pendingCount      = BlankSpot::where('kabupaten_id', $kabupatenId)->where('status_validasi', 'pending')->count();
        $diverifikasiCount = BlankSpot::where('kabupaten_id', $kabupatenId)->where('status_validasi', 'sedang_diverifikasi')->count();
        $approvedCount     = $totalData;
        $rejectedCount     = BlankSpot::where('kabupaten_id', $kabupatenId)->where('status_validasi', 'rejected')->count();
        $revisiCount       = BlankSpot::where('kabupaten_id', $kabupatenId)->whereIn('status_validasi', ['revisi', 'perlu_revisi'])->count();

        $networkStats = (clone $baseQuery)
            ->select('status_jaringan', DB::raw('count(*) as total'))
            ->groupBy('status_jaringan')
            ->pluck('total', 'status_jaringan')
            ->toArray();

        $geografisStats = (clone $baseQuery)
            ->whereNotNull('kondisi_geografis')
            ->select('kondisi_geografis', DB::raw('count(*) as total'))
            ->groupBy('kondisi_geografis')
            ->pluck('total', 'kondisi_geografis')
            ->toArray();

        $statusValidasiStats = [
            'Pending'      => $pendingCount,
            'Disetujui'    => $approvedCount,
            'Ditolak'      => $rejectedCount,
            'Perlu Revisi' => $revisiCount,
        ];

        // Recent Submissions for Operator
        $recentSubmissions = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('kabupaten_id', $kabupatenId)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return [
            'totalData'           => $totalData,
            'pendingCount'        => $pendingCount,
            'diverifikasiCount'   => $diverifikasiCount,
            'approvedCount'       => $approvedCount,
            'rejectedCount'       => $rejectedCount,
            'revisiCount'         => $revisiCount,
            'networkStats'        => $networkStats,
            'geografisStats'      => $geografisStats,
            'statusValidasiStats' => $statusValidasiStats,
            'recentSubmissions'   => $recentSubmissions,
        ];
    }
}
