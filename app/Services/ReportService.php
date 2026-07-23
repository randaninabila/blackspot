<?php

namespace App\Services;

use App\Models\BlankSpot;
use App\Models\User;
use App\Exports\BlankSpotExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportService
{
    /**
     * Generate PDF Report with signature area & officer details
     */
    public function generatePdf(array $filters, User $user, ?int $kabupatenId = null)
    {
        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator'])
            ->where('status_validasi', 'approved');

        $effectiveKabupatenId = $kabupatenId ?? ($filters['kabupaten_id'] ?? null);

        if ($effectiveKabupatenId) {
            $query->where('kabupaten_id', $effectiveKabupatenId);
        }

        if (!empty($filters['tahun'])) {
            $query->where('tahun', $filters['tahun']);
        }

        if (!empty($filters['prioritas'])) {
            $query->where('prioritas', $filters['prioritas']);
        }

        if (!empty($filters['status_jaringan'])) {
            $query->where('status_jaringan', $filters['status_jaringan']);
        }

        $data = $query->orderBy('kabupaten_id')->orderBy('kecamatan_id')->get();

        $pdf = Pdf::loadView('exports.blankspot-pdf', [
            'data'         => $data,
            'user'         => $user,
            'namaPejabat'  => $filters['nama_pejabat'] ?? $user->nama,
            'nipPejabat'   => $filters['nip_pejabat'] ?? '-',
            'tanggalCetak' => now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'landscape');

        AuditLogService::log("Mengunduh Laporan PDF Blank Spot (" . count($data) . " record)", request(), $user->id);

        return $pdf->download('laporan-blankspot-' . now()->format('Ymd-His') . '.pdf');
    }

    /**
     * Generate Excel Report
     */
    public function generateExcel(array $filters, User $user, ?int $kabupatenId = null)
    {
        $effectiveKabupatenId = $kabupatenId ?? ($filters['kabupaten_id'] ?? null);

        AuditLogService::log("Mengunduh Laporan Excel Blank Spot", request(), $user->id);

        return Excel::download(
            new BlankSpotExport($filters, $effectiveKabupatenId),
            'laporan-blankspot-' . date('Ymd-His') . '.xlsx'
        );
    }
}
