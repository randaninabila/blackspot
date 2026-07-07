<?php

namespace App\Http\Controllers;

use App\Models\BlankSpot;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidationController extends Controller
{
    /**
     * Halaman index validasi
     */
    public function index(Request $request)
    {
        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator']);

        if ($request->has('kabupaten_id') && $request->kabupaten_id) {
            $query->where('kabupaten_id', $request->kabupaten_id);
        }
        if ($request->has('status') && $request->status && $request->status != 'all') {
            $query->where('status_validasi', $request->status);
        }
        if ($request->has('tahun') && $request->tahun) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('kabupaten', function($sq) use ($search) {
                    $sq->where('nama_kabupaten', 'LIKE', "%{$search}%");
                })->orWhereHas('kecamatan', function($sq) use ($search) {
                    $sq->where('nama_kecamatan', 'LIKE', "%{$search}%");
                })->orWhereHas('desa', function($sq) use ($search) {
                    $sq->where('nama_desa', 'LIKE', "%{$search}%");
                });
            });
        }

        if (!$request->has('status')) {
            $query->where('status_validasi', 'pending');
        }

        $blankSpots = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $totalMenunggu = BlankSpot::where('status_validasi', 'pending')->count();
        $totalDisetujui = BlankSpot::where('status_validasi', 'approved')->count();
        $totalDitolak = BlankSpot::where('status_validasi', 'rejected')->count();
        
        $validasiMenunggu = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator'])
            ->where('status_validasi', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalData = BlankSpot::count();
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        
        $tahunStats = BlankSpot::selectRaw('YEAR(tahun) as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();
        $nilaiRataRata = $tahunStats->avg('total') ?? 0;
        
        $nilaiTertinggiData = BlankSpot::selectRaw('YEAR(tahun) as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('total', 'desc')
            ->first();
        $nilaiTertinggi = $nilaiTertinggiData ? $nilaiTertinggiData->total : 0;
        $tahunTertinggi = $nilaiTertinggiData ? $nilaiTertinggiData->year : '-';
        
        $nilaiTerendahData = BlankSpot::selectRaw('YEAR(tahun) as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('total', 'asc')
            ->first();
        $nilaiTerendah = $nilaiTerendahData ? $nilaiTerendahData->total : 0;
        $tahunTerendah = $nilaiTerendahData ? $nilaiTerendahData->year : '-';

        return view('admin.validasi.index', compact(
            'blankSpots', 'totalMenunggu', 'totalDisetujui', 'totalDitolak',
            'kabupatens', 'validasiMenunggu',
            'totalData', 'nilaiRataRata', 'nilaiTertinggi', 'tahunTertinggi',
            'nilaiTerendah', 'tahunTerendah'
        ));
    }

    /**
     * Detail validasi
     */
    public function show($id)
    {
        $blankSpot = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator', 'validator'])
            ->findOrFail($id);
        return view('admin.validasi.show', compact('blankSpot'));
    }

    /**
     * Edit validasi
     */
    public function edit($id)
    {
        $blankSpot = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])->findOrFail($id);
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        $kecamatans = Kecamatan::where('kabupaten_id', $blankSpot->kabupaten_id)->get();
        $desas = Desa::where('kecamatan_id', $blankSpot->kecamatan_id)->get();

        return view('admin.validasi.edit', compact('blankSpot', 'kabupatens', 'kecamatans', 'desas'));
    }

    /**
     * Update validasi
     */
    public function update(Request $request, $id)
    {
        $blankSpot = BlankSpot::findOrFail($id);

        $validated = $request->validate([
            'kabupaten_id' => 'required|exists:kabupaten,id',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'desa_id' => 'nullable|exists:desa,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'status_validasi' => 'required|in:pending,approved,rejected',
            'keterangan' => 'nullable|string',
        ]);

        $blankSpot->update($validated);

        return redirect()->route('admin.validasi.index')
            ->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Hapus validasi
     */
    public function destroy($id)
    {
        try {
            $blankSpot = BlankSpot::findOrFail($id);
            $blankSpot->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Setujui data - PERBAIKAN
     */
    public function setujui($id)
    {
        try {
            $blankSpot = BlankSpot::findOrFail($id);
            
            if ($blankSpot->status_validasi === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data sudah disetujui sebelumnya!'
                ], 400);
            }
            
            $blankSpot->status_validasi = 'approved';
            $blankSpot->validated_by = auth()->id();
            $blankSpot->validated_at = now();
            $blankSpot->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disetujui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tolak data - PERBAIKAN
     */
    public function tolak($id)
    {
        try {
            $blankSpot = BlankSpot::findOrFail($id);
            
            if ($blankSpot->status_validasi === 'rejected') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data sudah ditolak sebelumnya!'
                ], 400);
            }
            
            $blankSpot->status_validasi = 'rejected';
            $blankSpot->validated_by = auth()->id();
            $blankSpot->validated_at = now();
            $blankSpot->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditolak!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validasi massal - Setujui banyak data sekaligus
     */
    public function massalSetujui(Request $request)
    {
        $ids = $request->ids;
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }
        
        BlankSpot::whereIn('id', $ids)->update([
            'status_validasi' => 'approved',
            'validated_by' => auth()->id(),
            'validated_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => count($ids) . ' data berhasil disetujui'
        ]);
    }

    /**
     * Validasi massal - Tolak banyak data sekaligus
     */
    public function massalTolak(Request $request)
    {
        $ids = $request->ids;
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }
        
        BlankSpot::whereIn('id', $ids)->update([
            'status_validasi' => 'rejected',
            'validated_by' => auth()->id(),
            'validated_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => count($ids) . ' data berhasil ditolak'
        ]);
    }
}