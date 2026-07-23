<?php

namespace App\Http\Controllers;

use App\Models\BlankSpot;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Http\Requests\ValidationActionRequest;
use App\Services\ValidationService;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class ValidationController extends Controller
{
    protected ValidationService $validationService;

    public function __construct(ValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    /**
     * Halaman index validasi (Admin Only)
     */
    public function index(Request $request)
    {
        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator']);

        if ($request->filled('kabupaten_id')) {
            $query->where('kabupaten_id', $request->kabupaten_id);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status_validasi', $request->status);
        } else {
            // Default: tampilkan yang pending
            if (!$request->has('status')) {
                $query->where('status_validasi', 'pending');
            }
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('kabupaten', fn($sq) => $sq->where('nama_kabupaten', 'LIKE', "%{$search}%"))
                  ->orWhereHas('kecamatan', fn($sq) => $sq->where('nama_kecamatan', 'LIKE', "%{$search}%"))
                  ->orWhereHas('desa', fn($sq) => $sq->where('nama_desa', 'LIKE', "%{$search}%"))
                  ->orWhere('nama_lokasi', 'LIKE', "%{$search}%");
            });
        }

        $blankSpots = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $totalMenunggu  = BlankSpot::where('status_validasi', 'pending')->count();
        $totalDisetujui = BlankSpot::where('status_validasi', 'approved')->count();
        $totalDitolak   = BlankSpot::where('status_validasi', 'rejected')->count();
        $totalRevisi    = BlankSpot::whereIn('status_validasi', ['revisi', 'perlu_revisi'])->count();

        $validasiMenunggu = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator'])
            ->where('status_validasi', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalData  = BlankSpot::count();
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();

        $tahunStats = BlankSpot::selectRaw('tahun as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        $nilaiRataRata = $tahunStats->avg('total') ?? 0;

        $nilaiTertinggiData = BlankSpot::selectRaw('tahun as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('total', 'desc')
            ->first();
        $nilaiTertinggi = $nilaiTertinggiData ? $nilaiTertinggiData->total : 0;
        $tahunTertinggi = $nilaiTertinggiData ? $nilaiTertinggiData->year : '-';

        $nilaiTerendahData = BlankSpot::selectRaw('tahun as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('total', 'asc')
            ->first();
        $nilaiTerendah = $nilaiTerendahData ? $nilaiTerendahData->total : 0;
        $tahunTerendah = $nilaiTerendahData ? $nilaiTerendahData->year : '-';

        return view('admin.validasi.index', compact(
            'blankSpots', 'totalMenunggu', 'totalDisetujui', 'totalDitolak', 'totalRevisi',
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
     * Form edit validasi
     */
    public function edit($id)
    {
        $blankSpot  = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])->findOrFail($id);
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        $kecamatans = Kecamatan::where('kabupaten_id', $blankSpot->kabupaten_id)->get();
        $desas      = Desa::where('kecamatan_id', $blankSpot->kecamatan_id)->get();

        return view('admin.validasi.edit', compact('blankSpot', 'kabupatens', 'kecamatans', 'desas'));
    }

    /**
     * Update data dari form validasi edit
     */
    public function update(Request $request, $id)
    {
        $blankSpot = BlankSpot::findOrFail($id);

        $validated = $request->validate([
            'kabupaten_id'    => 'required|exists:kabupaten,id',
            'kecamatan_id'    => 'required|exists:kecamatan,id',
            'desa_id'         => 'nullable|exists:desa,id',
            'latitude'        => 'required|numeric|between:-90,90',
            'longitude'       => 'required|numeric|between:-180,180',
            'tahun'           => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'status_validasi' => 'required|in:pending,approved,rejected,revisi,perlu_revisi',
            'catatan_revisi'  => 'nullable|string|max:1000',
            'keterangan'      => 'nullable|string',
        ]);

        $blankSpot->update($validated);

        AuditLogService::log("Admin memperbarui data validasi Blank Spot ID: {$id}", $request);

        return redirect()->route('admin.validasi.index')
            ->with('success', 'Data validasi berhasil diperbarui!');
    }

    /**
     * Hapus validasi
     */
    public function destroy($id)
    {
        try {
            $blankSpot = BlankSpot::findOrFail($id);
            $blankSpot->delete();

            AuditLogService::log("Admin menghapus data validasi Blank Spot ID: {$id}", request());

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil dihapus!'
                ]);
            }

            return redirect()->route('admin.validasi.index')->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Setujui data (Approve)
     */
    public function setujui($id)
    {
        try {
            $blankSpot = BlankSpot::findOrFail($id);
            $admin = Auth::user();

            if ($blankSpot->status_validasi === 'approved') {
                $msg = 'Data sudah disetujui sebelumnya!';
                return request()->wantsJson() 
                    ? response()->json(['success' => false, 'message' => $msg], 400)
                    : back()->with('error', $msg);
            }

            $this->validationService->approve($blankSpot, $admin);

            $msg = 'Data blank spot berhasil disetujui!';
            if (request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }

            return back()->with('success', $msg);
        } catch (InvalidArgumentException $e) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menyetujui data: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Gagal menyetujui data.');
        }
    }

    /**
     * Tolak data (Reject)
     */
    public function tolak(Request $request, $id)
    {
        try {
            $blankSpot = BlankSpot::findOrFail($id);
            $admin = Auth::user();
            $reason = $request->input('catatan_revisi') ?? $request->input('alasan_revisi');

            if ($blankSpot->status_validasi === 'rejected') {
                $msg = 'Data sudah ditolak sebelumnya!';
                return request()->wantsJson() 
                    ? response()->json(['success' => false, 'message' => $msg], 400)
                    : back()->with('error', $msg);
            }

            $this->validationService->reject($blankSpot, $admin, $reason);

            $msg = 'Data blank spot berhasil ditolak!';
            if (request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menolak data: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Gagal menolak data.');
        }
    }

    /**
     * Kembalikan data untuk revisi (Perlu Revisi)
     */
    public function revisi(ValidationActionRequest $request, $id)
    {
        try {
            $blankSpot = BlankSpot::findOrFail($id);
            $admin = Auth::user();
            $catatan = $request->input('catatan_revisi') ?? $request->input('alasan_revisi');

            $this->validationService->requestRevision($blankSpot, $admin, $catatan);

            $msg = 'Data berhasil dikembalikan ke Operator untuk revisi.';
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }

            return back()->with('success', $msg);
        } catch (InvalidArgumentException $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memproses revisi: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Gagal memproses revisi.');
        }
    }

    /**
     * Validasi massal - Setujui banyak data sekaligus
     */
    public function massalSetujui(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        $count = $this->validationService->massApprove($ids, Auth::user());

        return response()->json([
            'success' => true,
            'message' => "{$count} data berhasil disetujui!"
        ]);
    }

    /**
     * Validasi massal - Tolak banyak data sekaligus
     */
    public function massalTolak(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        $count = $this->validationService->massReject($ids, Auth::user());

        return response()->json([
            'success' => true,
            'message' => "{$count} data berhasil ditolak!"
        ]);
    }
}