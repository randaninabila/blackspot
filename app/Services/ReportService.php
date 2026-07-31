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

        $sigData = $this->getSignatureData($user, $effectiveKabupatenId, $filters);

        $pdf = Pdf::loadView('exports.blankspot-pdf', array_merge([
            'data'  => $data,
            'user'  => $user,
        ], $sigData))->setPaper('a4', 'landscape');

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

    public function generateBeritaAcaraPdf(BlankSpot $blankSpot, User $user, array $options = [])
    {
        $sigData = $this->getSignatureData($user, $blankSpot->kabupaten_id, $options);

        $pdf = Pdf::loadView('exports.berita-acara-pdf', array_merge([
            'blankSpot'    => $blankSpot,
            'user'         => $user,
        ], $sigData))->setPaper('a4', 'portrait');

        AuditLogService::log("Mengunduh Berita Acara PDF Blank Spot ID: {$blankSpot->id}", request(), $user->id);

        return $pdf->download('berita-acara-blankspot-' . $blankSpot->id . '-' . now()->format('Ymd-His') . '.pdf');
    }

    /**
     * Helper to prepare signature details dynamically based on logged in user's Kabupaten
     */
    protected function getSignatureData(User $user, ?int $kabupatenId = null, array $extra = []): array
    {
        $user->loadMissing('kabupaten');

        $effectiveKabId = $user->kabupaten_id ?? $kabupatenId;
        $kabupaten = $user->kabupaten;

        if (!$kabupaten && $effectiveKabId && is_numeric($effectiveKabId)) {
            $kabupaten = \App\Models\Kabupaten::find($effectiveKabId);
        }

        $namaKabupaten = $kabupaten ? $kabupaten->nama_kabupaten : 'Provinsi Sumatera Utara';

        $ibuKotaMap = [
            'Kabupaten Karo'               => 'Kabanjahe',
            'Kabupaten Deli Serdang'       => 'Lubuk Pakam',
            'Kabupaten Simalungun'         => 'Raya',
            'Kabupaten Langkat'            => 'Stabat',
            'Kabupaten Asahan'             => 'Kisaran',
            'Kabupaten Labuhanbatu'        => 'Rantau Prapat',
            'Kabupaten Toba'               => 'Balige',
            'Kabupaten Samosir'            => 'Pangururan',
            'Kabupaten Tapanuli Utara'     => 'Tarutung',
            'Kabupaten Tapanuli Tengah'    => 'Pandan',
            'Kabupaten Tapanuli Selatan'   => 'Sipirok',
            'Kabupaten Mandailing Natal'   => 'Panyabungan',
            'Kabupaten Dairi'              => 'Sidikalang',
            'Kabupaten Pakpak Bharat'      => 'Salak',
            'Kabupaten Humbang Hasundutan' => 'Dolok Sanggul',
            'Kabupaten Nias'               => 'Gido',
            'Kabupaten Nias Selatan'       => 'Teluk Dalam',
            'Kabupaten Nias Utara'         => 'Lotu',
            'Kabupaten Nias Barat'         => 'Lahomi',
            'Kabupaten Batubara'           => 'Limapuluh',
            'Kabupaten Padang Lawas'       => 'Sibuhuan',
            'Kabupaten Padang Lawas Utara' => 'Gunung Tua',
            'Kabupaten Labuhanbatu Utara'  => 'Aek Kanopan',
            'Kabupaten Labuhanbatu Selatan'=> 'Kota Pinang',
        ];

        if ($kabupaten) {
            if (isset($ibuKotaMap[$kabupaten->nama_kabupaten])) {
                $namaKota = $ibuKotaMap[$kabupaten->nama_kabupaten];
            } else {
                $namaKota = trim(str_replace(['Kabupaten ', 'Kota '], '', $kabupaten->nama_kabupaten));
            }
        } else {
            $namaKota = 'Medan';
        }

        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $tanggalCetak = date('j') . ' ' . $bulanIndo[(int)date('n')] . ' ' . date('Y');

        $nipRaw = $extra['nip_pejabat'] ?? ($user->nip ?? null);
        if ($nipRaw && $nipRaw !== '-' && trim($nipRaw) !== '') {
            $nipFormatted = ' ' . trim($nipRaw);
        } else {
            $nipFormatted = '';
        }

        return [
            'namaKota'      => $namaKota,
            'namaKabupaten' => $namaKabupaten,
            'tanggalCetak'  => $tanggalCetak,
            'nipFormatted'  => $nipFormatted,
        ];
    }
}
