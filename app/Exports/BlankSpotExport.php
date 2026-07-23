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
        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator'])
            ->where('status_validasi', 'approved');

        if ($this->kabupatenId) {
            $query->where('kabupaten_id', $this->kabupatenId);
        } elseif (!empty($this->filters['kabupaten_id'])) {
            $query->where('kabupaten_id', $this->filters['kabupaten_id']);
        }

        if (!empty($this->filters['tahun'])) {
            $query->where('tahun', $this->filters['tahun']);
        }

        if (!empty($this->filters['prioritas'])) {
            $query->where('prioritas', $this->filters['prioritas']);
        }

        if (!empty($this->filters['status_jaringan'])) {
            $query->where('status_jaringan', $this->filters['status_jaringan']);
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
            $blankSpot->keterangan ?? '-',
            $blankSpot->status_label,
            $blankSpot->creator->nama ?? '-',
        ];
    }
}