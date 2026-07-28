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
        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator', 'validator', 'photos']);

        $status = $filters['status'] ?? ($filters['status_validasi'] ?? null);
        if ($status && $status !== 'all') {
            $query->where('status_validasi', $status);
        } elseif (!$status) {
            $query->where('status_validasi', 'approved');
        }

        $effectiveKabupatenId = $kabupatenId ?? ($filters['kabupaten_id'] ?? null);
        if ($effectiveKabupatenId && $effectiveKabupatenId !== 'all') {
            $query->where('kabupaten_id', $effectiveKabupatenId);
        }

        if (!empty($filters['tahun'])) {
            $query->where('tahun', $filters['tahun']);
        }

        if (!empty($filters['semester'])) {
            $query->where('semester', $filters['semester']);
        }

        if (!empty($filters['prioritas'])) {
            $query->where('prioritas', $filters['prioritas']);
        }

        if (!empty($filters['status_jaringan'])) {
            $query->where('status_jaringan', $filters['status_jaringan']);
        }

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function ($q) use ($s) {
                $q->whereHas('kabupaten', fn($sq) => $sq->where('nama_kabupaten', 'like', "%{$s}%"))
                  ->orWhereHas('kecamatan', fn($sq) => $sq->where('nama_kecamatan', 'like', "%{$s}%"))
                  ->orWhereHas('desa', fn($sq) => $sq->where('nama_desa', 'like', "%{$s}%"))
                  ->orWhere('nama_lokasi', 'like', "%{$s}%");
            });
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

    /**
     * Generate CSV Report
     */
    public function generateCsv(array $filters, User $user, ?int $kabupatenId = null)
    {
        $effectiveKabupatenId = $kabupatenId ?? ($filters['kabupaten_id'] ?? null);

        AuditLogService::log("Mengunduh Laporan CSV Blank Spot", request(), $user->id);

        return Excel::download(
            new BlankSpotExport($filters, $effectiveKabupatenId),
            'laporan-blankspot-' . date('Ymd-His') . '.csv',
            \Maatwebsite\Excel\Excel::CSV
        );
    }

    /**
     * Generate Berita Acara PDF
     */
    public function generateBeritaAcaraPdf(BlankSpot $blankSpot, User $user, array $options = [])
    {
        $pdf = Pdf::loadView('exports.berita-acara-pdf', [
            'blankSpot'    => $blankSpot,
            'user'         => $user,
            'namaPejabat'  => $options['nama_pejabat'] ?? $user->nama,
            'nipPejabat'   => $options['nip_pejabat'] ?? '-',
            'tanggalCetak' => now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        AuditLogService::log("Mengunduh Berita Acara PDF Blank Spot ID: {$blankSpot->id}", request(), $user->id);

        return $pdf->download('berita-acara-blankspot-' . $blankSpot->id . '-' . now()->format('Ymd-His') . '.pdf');
    }
}
