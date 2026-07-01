<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kabupaten;
use App\Models\BlankSpot;

class MapController extends Controller
{
    public function index()
    {
        // Ambil semua kabupaten dari database untuk dropdown
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();

        // Ambil tahun unik dari data blank spot untuk filter tahun
        $tahuns = BlankSpot::selectRaw('DISTINCT tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('map', compact('kabupatens', 'tahuns'));
    }

    public function getGeoJson($kabupatenId)
    {
        // Cari kabupaten
        $kabupaten = Kabupaten::findOrFail($kabupatenId);

        // Ambil semua titik blank spot untuk kabupaten ini
        $spots = BlankSpot::where('kabupaten_id', $kabupatenId)
            ->where('status_validasi', 'approved')
            ->get();

        // Format GeoJSON untuk titik-titik blank spot
        $features = [];

        foreach ($spots as $spot) {
            $features[] = [
                "type" => "Feature",
                "properties" => [
                    "name" => $kabupaten->nama_kabupaten,
                    "desa" => $spot->desa->nama_desa ?? '-',
                    "kecamatan" => $spot->kecamatan->nama_kecamatan ?? '-',
                    "tahun" => $spot->tahun,
                    "status" => $spot->keterangan ?? 'Blank Spot',
                ],
                "geometry" => [
                    "type" => "Point",
                    "coordinates" => [
                        (float) $spot->longitude,
                        (float) $spot->latitude
                    ]
                ]
            ];
        }


        if (empty($features)) {
            return response()->json([
                "type" => "FeatureCollection",
                "features" => []
            ]);
        }

        return response()->json([
            "type" => "FeatureCollection",
            "features" => $features
        ]);
    }
}