<?php

namespace App\Http\Controllers;

use App\Models\BlankSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
        public function exportPdf(Request $request)
    {
        $query = BlankSpot::with([
            'kabupaten',
            'kecamatan',
            'desa',
            'creator'
        ])->where('status_validasi', 'approved');

        if ($request->kabupaten_id) {
            $query->where('kabupaten_id', $request->kabupaten_id);
        }

        if ($request->tahun) {
            $query->where('tahun', $request->tahun);
        }

        $data = $query->orderBy('kabupaten_id')
                    ->orderBy('kecamatan_id')
                    ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'exports.blankspot-pdf',
            compact('data')
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'laporan-blankspot-'.now()->format('Ymd-His').'.pdf'
        );
    }

    public function exportExcel(Request $request)
    {
        if (class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\BlankSpotExport($request->all(), null),
                'laporan-blankspot-' . date('Ymd') . '.xlsx'
            );
        }

        // Fallback: CSV download tanpa package
        return $this->downloadCsv(null, $request->all());
    }

        public function exportPdfUser(Request $request)
    {
        $query = BlankSpot::with([
            'kabupaten',
            'kecamatan',
            'desa',
            'creator'
        ])->where('status_validasi', 'approved');

        if ($request->kabupaten_id) {
            $query->where('kabupaten_id', $request->kabupaten_id);
        }

        if ($request->tahun) {
            $query->where('tahun', $request->tahun);
        }

        $data = $query->orderBy('kabupaten_id')
                    ->orderBy('kecamatan_id')
                    ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'exports.blankspot-pdf',
            compact('data')
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'laporan-blankspot-' . date('Ymd') . '.pdf'
        );
    }

        public function exportExcelUser(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\BlankSpotExport($request->all(), null),
            'laporan-blankspot-' . date('Ymd') . '.xlsx'
        );
    }

    private function downloadCsv(?int $userId, array $filters)
    {
        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('status_validasi', 'approved');

        if ($userId) $query->where('created_by', $userId);
        if (!empty($filters['kabupaten_id'])) $query->where('kabupaten_id', $filters['kabupaten_id']);
        if (!empty($filters['tahun']))        $query->where('tahun', $filters['tahun']);

        $data = $query->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan-blankspot-' . date('Ymd') . '.csv"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Kabupaten/Kota', 'Kecamatan', 'Desa', 'Latitude', 'Longitude', 'Tahun', 'Keterangan']);
            foreach ($data as $i => $row) {
                fputcsv($file, [
                    $i + 1,
                    $row->kabupaten->nama_kabupaten ?? '-',
                    $row->kecamatan->nama_kecamatan ?? '-',
                    $row->desa->nama_desa ?? '-',
                    $row->latitude,
                    $row->longitude,
                    $row->tahun,
                    $row->keterangan ?? '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}