<?php

namespace App\Http\Controllers;

use App\Models\BlankSpot;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Export PDF untuk Admin
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        return $this->reportService->generatePdf($request->all(), $user);
    }

    /**
     * Export Excel untuk Admin
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        return $this->reportService->generateExcel($request->all(), $user);
    }

    /**
     * Export PDF untuk Operator (hanya data Kabupaten milik user)
     */
    public function exportPdfUser(Request $request)
    {
        $user = Auth::user();
        return $this->reportService->generatePdf($request->all(), $user, $user->kabupaten_id);
    }

    /**
     * Export Excel untuk Operator (hanya data Kabupaten milik user)
     */
    public function exportExcelUser(Request $request)
    {
        $user = Auth::user();
        return $this->reportService->generateExcel($request->all(), $user, $user->kabupaten_id);
    }

    /**
     * Export CSV untuk Admin
     */
    public function exportCsv(Request $request)
    {
        $user = Auth::user();
        return $this->reportService->generateCsv($request->all(), $user);
    }

    /**
     * Export CSV untuk Operator
     */
    public function exportCsvUser(Request $request)
    {
        $user = Auth::user();
        return $this->reportService->generateCsv($request->all(), $user, $user->kabupaten_id);
    }

    /**
     * Generate Berita Acara PDF untuk satu lokasi blank spot
     */
    public function beritaAcaraPdf(Request $request, $id)
    {
        $user = Auth::user();
        $blankSpot = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator', 'verifikator'])->findOrFail($id);

        // Security check
        if ($user->isOperator() && (int) $user->kabupaten_id !== (int) $blankSpot->kabupaten_id) {
            abort(403, 'Akses ditolak. Anda tidak berhak mengunduh Berita Acara wilayah ini.');
        }

        return $this->reportService->generateBeritaAcaraPdf($blankSpot, $user, $request->all());
    }
}