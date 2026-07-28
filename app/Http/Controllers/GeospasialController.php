<?php

namespace App\Http\Controllers;

use App\Models\BlankSpot;
use App\Models\Kabupaten;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeospasialController extends Controller
{
    /**
     * Menampilkan halaman peta geospasial
     */
    public function index()
    {
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        $tahuns     = BlankSpot::where('status_validasi', 'approved')->distinct('tahun')->pluck('tahun');
        $spots      = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator', 'validator', 'photos'])->where('status_validasi', 'approved')->get();

        return view('admin.geospasial.index', compact('kabupatens', 'tahuns', 'spots'));
    }

    /**
     * API endpoint untuk detail geospasial satu Kabupaten saat diklik di peta
     */
    public function getKabupatenData($id)
    {
        $kabupaten = Kabupaten::findOrFail($id);

        $spots = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator', 'validator', 'photos'])
            ->where('kabupaten_id', $id)
            ->where('status_validasi', 'approved')
            ->get();

        $totalBlankSpot = $spots->count();
        $totalDesa      = $spots->pluck('desa_id')->unique()->count();

        // Statistik Status Jaringan
        $networkStats = $spots->groupBy('status_jaringan')->map->count();

        // Statistik Prioritas (P1-P10)
        $priorityStats = $spots->groupBy('prioritas')->map->count();

        // Daftar Titik Detail
        $daftarTitik = $spots->map(function ($spot) {
            $firstPhoto = $spot->photos->first();
            return [
                'id'                => $spot->id,
                'latitude'          => (float) $spot->latitude,
                'longitude'         => (float) $spot->longitude,
                'lat'               => (float) $spot->latitude,
                'lng'               => (float) $spot->longitude,
                'radius'            => (float) ($spot->radius ?? 0),
                'prioritas'         => $spot->prioritas ? 'P' . $spot->prioritas : '-',
                'prioritas_num'     => $spot->prioritas ?? 0,
                'status_jaringan'   => $spot->status_jaringan ?? '-',
                'kondisi_geografis' => $spot->kondisi_geografis ?? '-',
                'jumlah_penduduk'   => $spot->jumlah_penduduk ?? '-',
                'jarak_ibukota'     => $spot->jarak_ibukota ?? 0,
                'nama_lokasi'       => $spot->nama_lokasi ?? ($spot->desa->nama_desa ?? '-'),
                'kecamatan'         => $spot->kecamatan->nama_kecamatan ?? '-',
                'desa'              => $spot->desa->nama_desa ?? '-',
                'tahun'             => $spot->tahun,
                'keterangan'        => $spot->status_jaringan ?? '-',
                'marker_color'      => $this->getMarkerColor($spot->prioritas, $spot->status_jaringan),
                'foto_url'          => $firstPhoto ? $firstPhoto->url : null,
                'photos'            => $spot->photos->map(fn($p) => ['id' => $p->id, 'url' => $p->url, 'jenis' => $p->jenis_foto]),
            ];
        });

        $daftarDesa = $spots->pluck('desa.nama_desa')->filter()->unique()->values()->toArray();

        return response()->json([
            'success'          => true,
            'nama_kabupaten'   => $kabupaten->nama_kabupaten,
            'total_blank_spot' => $totalBlankSpot,
            'total_desa'       => $totalDesa,
            'network_stats'    => $networkStats,
            'priority_stats'   => $priorityStats,
            'daftar_desa'      => $daftarDesa,
            'daftar_titik'     => $daftarTitik,
        ]);
    }

    /**
     * API endpoint untuk mendapatkan semua data blank spot yang sudah approved
     */
    public function getAllSpots(Request $request)
    {
        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator', 'validator', 'photos'])
            ->where('status_validasi', 'approved');

        if ($request->filled('kabupaten_id') && $request->kabupaten_id !== 'all') {
            $query->where('kabupaten_id', $request->kabupaten_id);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        if ($request->filled('status_jaringan')) {
            $query->where('status_jaringan', $request->status_jaringan);
        }

        $spots = $query->get();

        $formattedSpots = $spots->map(function ($spot) {
            $namaLokasi = $spot->nama_lokasi ?? ($spot->desa->nama_desa ?? ($spot->kecamatan->nama_kecamatan ?? ($spot->kabupaten->nama_kabupaten ?? '-')));

            $markerColor = $this->getMarkerColor($spot->prioritas, $spot->status_jaringan);
            $firstPhoto  = $spot->photos->first();

            return [
                'id'                => $spot->id,
                'latitude'          => (float) $spot->latitude,
                'longitude'         => (float) $spot->longitude,
                'lat'               => (float) $spot->latitude,
                'lng'               => (float) $spot->longitude,
                'radius'            => (float) ($spot->radius ?? 0),
                'prioritas'         => $spot->prioritas ? 'P' . $spot->prioritas : '-',
                'prioritas_num'     => $spot->prioritas ?? 0,
                'status_jaringan'   => $spot->status_jaringan ?? '-',
                'kondisi_geografis' => $spot->kondisi_geografis ?? '-',
                'jumlah_penduduk'   => $spot->jumlah_penduduk ?? '-',
                'jarak_ibukota'     => $spot->jarak_ibukota ?? 0,
                'kabupaten'         => $spot->kabupaten ? $spot->kabupaten->nama_kabupaten : '-',
                'kecamatan'         => $spot->kecamatan ? $spot->kecamatan->nama_kecamatan : '-',
                'desa'              => $spot->desa ? $spot->desa->nama_desa : '-',
                'nama_lokasi'       => $namaLokasi,
                'keterangan'        => $spot->status_jaringan ?? 'Blank Spot',
                'status_sinyal'     => $spot->status_jaringan ?? 'Blank Spot',
                'status'            => $spot->status_validasi,
                'tahun'             => $spot->tahun,
                'marker_color'      => $markerColor,
                'foto_url'          => $firstPhoto ? $firstPhoto->url : null,
                'photos'            => $spot->photos->map(fn($p) => ['id' => $p->id, 'url' => $p->url, 'jenis' => $p->jenis_foto]),
                'status_validasi'   => $spot->status_validasi,
                'kabupaten_id'      => $spot->kabupaten_id,
                'created_at'        => $spot->created_at ? $spot->created_at->format('Y-m-d') : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $formattedSpots,
            'total'   => $formattedSpots->count()
        ]);
    }

    /**
     * Menentukan warna marker berdasarkan prioritas / status jaringan
     */
    private function getMarkerColor(?int $prioritas, ?string $statusJaringan): string
    {
        if ($prioritas >= 1 && $prioritas <= 3) {
            return '#dc2626'; // Merah pekat
        } elseif ($prioritas >= 4 && $prioritas <= 6) {
            return '#f97316'; // Oranye
        } elseif ($prioritas >= 7 && $prioritas <= 10) {
            return '#eab308'; // Kuning
        }

        if ($statusJaringan) {
            $jaringanLower = strtolower($statusJaringan);
            if (str_contains($jaringanLower, 'blank') || str_contains($jaringanLower, 'tidak ada')) {
                return '#ef4444';
            } elseif (str_contains($jaringanLower, '2g') || str_contains($jaringanLower, '3g') || str_contains($jaringanLower, 'lemah')) {
                return '#f59e0b';
            } elseif (str_contains($jaringanLower, '4g') || str_contains($jaringanLower, 'stabil')) {
                return '#22c55e';
            }
        }

        return '#ef4444';
    }
}