<?php

namespace App\Http\Controllers;

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
}