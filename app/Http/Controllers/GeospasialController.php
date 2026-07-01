<?php

namespace App\Http\Controllers;

use App\Models\BlankSpot;
use Illuminate\Http\Request;

class GeospasialController extends Controller
{
    /**
     * Menampilkan halaman peta geospasial
     */
    public function index()
    {
        return view('admin.geospasial.index');
    }

    /**
     * API endpoint untuk mendapatkan semua data blank spot yang sudah approved
     */
    public function getAllSpots(Request $request)
    {
        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('status_validasi', 'approved');

        // Filter by kabupaten_id
        if ($request->has('kabupaten_id') && $request->kabupaten_id != '' && $request->kabupaten_id != 'all') {
            $query->where('kabupaten_id', $request->kabupaten_id);
        }

        // Filter by tahun
        if ($request->has('tahun') && $request->tahun != '') {
            $query->where('tahun', $request->tahun);
        }

        $spots = $query->get();

        // Format data untuk response
        $formattedSpots = $spots->map(function ($spot) {
            // Ambil nama lokasi dari relasi
            $namaLokasi = '';
            if ($spot->desa) {
                $namaLokasi = $spot->desa->nama_desa;
            } elseif ($spot->kecamatan) {
                $namaLokasi = $spot->kecamatan->nama_kecamatan;
            } elseif ($spot->kabupaten) {
                $namaLokasi = $spot->kabupaten->nama_kabupaten;
            }

            // Tentukan warna marker berdasarkan keterangan
            $markerColor = $this->getMarkerColor($spot->keterangan);

            return [
                'id' => $spot->id,
                'latitude' => (float) $spot->latitude,
                'longitude' => (float) $spot->longitude,
                'lat' => (float) $spot->latitude,
                'lng' => (float) $spot->longitude,
                'kabupaten' => $spot->kabupaten ? $spot->kabupaten->nama_kabupaten : '-',
                'kecamatan' => $spot->kecamatan ? $spot->kecamatan->nama_kecamatan : '-',
                'desa' => $spot->desa ? $spot->desa->nama_desa : '-',
                'nama_lokasi' => $namaLokasi,
                'keterangan' => $spot->keterangan ?? 'Tidak ada keterangan',
                'status_sinyal' => $spot->keterangan ?? 'Tidak ada keterangan',
                'status' => $spot->keterangan ?? 'Blank Spot',
                'tahun' => $spot->tahun,
                'marker_color' => $markerColor,
                'status_validasi' => $spot->status_validasi,
                'kabupaten_id' => $spot->kabupaten_id,
                'created_at' => $spot->created_at ? $spot->created_at->format('Y-m-d') : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedSpots,
            'total' => $formattedSpots->count()
        ]);
    }

    /**
     * Menentukan warna marker berdasarkan keterangan
     * Sesuai dengan fungsi getColorByStatus() di dashboard
     */
    private function getMarkerColor($keterangan)
    {
        if (empty($keterangan)) {
            return '#ef4444'; // merah (default)
        }

        $keteranganLower = strtolower($keterangan);
        
        if (strpos($keteranganLower, 'lemah') !== false) {
            return '#f59e0b'; // orange
        } elseif (strpos($keteranganLower, 'stabil') !== false) {
            return '#3b82f6'; // blue
        } elseif (strpos($keteranganLower, 'ada sinyal') !== false) {
            return '#22c55e'; // green
        } elseif (strpos($keteranganLower, 'blank spot') !== false) {
            return '#ef4444'; // red
        }
        
        return '#ef4444'; // default red
    }
}