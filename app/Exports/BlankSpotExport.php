<?php

namespace App\Exports;

use App\Models\BlankSpot;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BlankSpotExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected array $filters;
    protected ?int $kabupatenId;

    public function __construct(array $filters = [], ?int $kabupatenId = null)
    {
        $this->filters = $filters;
        $this->kabupatenId = $kabupatenId;
    }

    public function query()
    {
        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator']);

        $status = $this->filters['status'] ?? ($this->filters['status_validasi'] ?? null);
        if ($status && $status !== 'all') {
            $query->where('status_validasi', $status);
        } elseif (!$status) {
            $query->where('status_validasi', 'approved');
        }

        $kabId = $this->kabupatenId ?? ($this->filters['kabupaten_id'] ?? null);
        if ($kabId && $kabId !== 'all') {
            $query->where('kabupaten_id', $kabId);
        }

        if (!empty($this->filters['tahun'])) {
            $query->where('tahun', $this->filters['tahun']);
        }

        if (!empty($this->filters['semester'])) {
            $query->where('semester', $this->filters['semester']);
        }

        if (!empty($this->filters['prioritas'])) {
            $query->where('prioritas', $this->filters['prioritas']);
        }

        if (!empty($this->filters['status_jaringan'])) {
            $query->where('status_jaringan', $this->filters['status_jaringan']);
        }

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(function ($q) use ($s) {
                $q->whereHas('kabupaten', fn($sq) => $sq->where('nama_kabupaten', 'like', "%{$s}%"))
                  ->orWhereHas('kecamatan', fn($sq) => $sq->where('nama_kecamatan', 'like', "%{$s}%"))
                  ->orWhereHas('desa', fn($sq) => $sq->where('nama_desa', 'like', "%{$s}%"))
                  ->orWhere('nama_lokasi', 'like', "%{$s}%");
            });
        }

        return $query->orderBy('kabupaten_id')->orderBy('kecamatan_id');
    }

    public function headings(): array
    {
        return [
            'No',
            'Kabupaten/Kota',
            'Kecamatan',
            'Desa',
            'Nama Lokasi',
            'Latitude',
            'Longitude',
            'Radius (m)',
            'Status Jaringan',
            'Prioritas',
            'Tahun',
            'Semester',
            'Keterangan',
            'Status Validasi',
            'Petugas Input',
        ];
    }

    private static int $rowNumber = 0;

    public function map($blankSpot): array
    {
        self::$rowNumber++;
        return [
            self::$rowNumber,
            $blankSpot->kabupaten->nama_kabupaten ?? '-',
            $blankSpot->kecamatan->nama_kecamatan ?? '-',
            $blankSpot->desa->nama_desa ?? '-',
            $blankSpot->nama_lokasi ?? '-',
            $blankSpot->latitude,
            $blankSpot->longitude,
            $blankSpot->radius ?? '-',
            $blankSpot->status_jaringan ?? '-',
            $blankSpot->prioritas ? 'Prioritas ' . $blankSpot->prioritas : '-',
            $blankSpot->tahun,
            $blankSpot->semester ? 'Semester ' . $blankSpot->semester : '-',
            $blankSpot->keterangan ?? '-',
            $blankSpot->status_label,
            $blankSpot->creator->nama ?? '-',
        ];
    }
}