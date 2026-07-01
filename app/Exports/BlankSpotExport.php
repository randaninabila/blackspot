<?php

namespace App\Exports;

use App\Models\BlankSpot;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BlankSpotExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected array $filters;
    protected ?int $userId;

    public function __construct(array $filters = [], ?int $userId = null)
    {
        $this->filters = $filters;
        $this->userId  = $userId;
    }

    public function collection()
    {
        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator'])
            ->where('status_validasi', 'approved');

        if ($this->userId) $query->where('created_by', $this->userId);
        if (!empty($this->filters['kabupaten_id'])) $query->where('kabupaten_id', $this->filters['kabupaten_id']);
        if (!empty($this->filters['tahun'])) $query->where('tahun', $this->filters['tahun']);

        return $query->orderBy('kabupaten_id')->get();
    }

    public function headings(): array
    {
        return ['No', 'Kabupaten/Kota', 'Kecamatan', 'Desa', 'Latitude', 'Longitude', 'Tahun', 'Keterangan', 'Status', 'Tanggal Input'];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $row->kabupaten->nama_kabupaten ?? '-',
            $row->kecamatan->nama_kecamatan ?? '-',
            $row->desa->nama_desa ?? '-',
            $row->latitude,
            $row->longitude,
            $row->tahun,
            $row->keterangan ?? '-',
            'Disetujui',
            $row->created_at->format('d/m/Y'),
        ];
    }
}