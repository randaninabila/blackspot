<?php

namespace App\Http\Controllers;

use App\Models\BlankSpot;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Http\Requests\StoreBlankSpotRequest;
use App\Http\Requests\UpdateBlankSpotRequest;
use App\Services\BlankSpotService;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BlankSpotController extends Controller
{
    protected BlankSpotService $blankSpotService;

    public function __construct(BlankSpotService $blankSpotService)
    {
        $this->blankSpotService = $blankSpotService;
    }

    /**
     * API JSON: Get kecamatan berdasarkan kabupaten_id (AJAX Only)
     */
    public function getKecamatan($kabupaten_id)
    {
        $kecamatans = Kecamatan::where('kabupaten_id', $kabupaten_id)
            ->orderBy('nama_kecamatan')
            ->get(['id', 'nama_kecamatan']);

        return response()->json($kecamatans);
    }

    /**
     * API JSON: Get desa berdasarkan kecamatan_id (AJAX Only)
     */
    public function getDesa($kecamatan_id)
    {
        $desas = Desa::where('kecamatan_id', $kecamatan_id)
            ->orderBy('nama_desa')
            ->get(['id', 'nama_desa']);

        return response()->json($desas);
    }

    /**
     * Serve foto dari storage secara publik tanpa 403 Forbidden
     */
    public function servePhoto(string $filename)
    {
        $path = str_contains($filename, '/') ? $filename : 'blank-spots/' . $filename;

        if (!Storage::disk('public')->exists($path)) {
            if (Storage::disk('public')->exists($filename)) {
                $path = $filename;
            } else {
                abort(404, 'Foto tidak ditemukan.');
            }
        }

        $fullPath = Storage::disk('public')->path($path);
        
        return response()->file($fullPath, [
            'Access-Control-Allow-Origin' => '*',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    // ============================================================
    // ADMIN METHODS
    // ============================================================

    /**
     * Admin - Index data blank spot
     */
    public function adminIndex(Request $request)
    {
        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator']);

        if ($request->filled('kabupaten_id')) {
            $query->where('kabupaten_id', $request->kabupaten_id);
        }
        if ($request->filled('status_validasi')) {
            $query->where('status_validasi', $request->status_validasi);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('kabupaten', fn($sq) => $sq->where('nama_kabupaten', 'like', "%{$s}%"))
                  ->orWhereHas('kecamatan', fn($sq) => $sq->where('nama_kecamatan', 'like', "%{$s}%"))
                  ->orWhereHas('desa', fn($sq) => $sq->where('nama_desa', 'like', "%{$s}%"))
                  ->orWhere('nama_lokasi', 'like', "%{$s}%");
            });
        }

        $blankSpots = $query->latest()->paginate(15)->withQueryString();
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        $tahuns     = BlankSpot::selectRaw('DISTINCT tahun')->orderBy('tahun', 'desc')->pluck('tahun');

        return view('admin.blank-spot.index', compact('blankSpots', 'kabupatens', 'tahuns'));
    }

    /**
     * Admin - Form create blank spot
     */
    public function create()
    {
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();

        return view('admin.blank-spot.create', compact('kabupatens', 'kecamatans'));
    }

    /**
     * Admin - Store blank spot
     */
    public function store(StoreBlankSpotRequest $request)
    {
        $user  = Auth::user();
        $data  = $request->validated();
        $photo = $request->file('foto');

        if ($user->isOperator()) {
            $inputKab = $request->input('kabupaten_id');
            if ($inputKab !== null && (int) $inputKab !== (int) $user->kabupaten_id) {
                abort(403, 'Anda tidak memiliki otorisasi untuk menambah data pada Kabupaten/Kota lain.');
            }
            $data['kabupaten_id'] = $user->kabupaten_id;
        }

        $bs = $this->blankSpotService->store($data, $user, $photo);

        return redirect()->back()->with('success', "Data blank spot berhasil ditambahkan dan masuk ke antrean validasi Admin.");
    }

    /**
     * Admin - Show detail blank spot
     */
    public function show($id)
    {
        $blankSpot = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator', 'validator'])->findOrFail($id);
        return view('admin.blank-spot.show', compact('blankSpot'));
    }

    /**
     * Admin - Edit blank spot
     */
    public function edit($id)
    {
        $blankSpot = BlankSpot::findOrFail($id);

        if ($blankSpot->status_validasi === 'approved') {
            return redirect()->back()
                ->with('error', '⚠️ Data yang sudah DISETUJUI (Approved) tidak bisa diedit lagi.');
        }

        if (!str_contains(url()->previous(), '/edit')) {
            session(['blank_spot_return_url' => url()->previous()]);
        }

        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        $kecamatans = Kecamatan::where('kabupaten_id', $blankSpot->kabupaten_id)->orderBy('nama_kecamatan')->get();
        $desas      = Desa::where('kecamatan_id', $blankSpot->kecamatan_id)->orderBy('nama_desa')->get();

        return view('admin.blank-spot.edit', compact('blankSpot', 'kabupatens', 'kecamatans', 'desas'));
    }

    /**
     * Admin - Update blank spot
     */
    public function update(UpdateBlankSpotRequest $request, $id)
    {
        $blankSpot = BlankSpot::findOrFail($id);

        if ($blankSpot->status_validasi === 'approved') {
            return redirect()->back()
                ->with('error', '⚠️ Data yang sudah DISETUJUI (Approved) tidak dapat diubah.');
        }

        $data  = $request->validated();
        $user  = Auth::user();
        $photo = $request->file('foto');

        $this->blankSpotService->update($blankSpot, $data, $user, $photo);

        $returnUrl = session()->pull('blank_spot_return_url', route('admin.blank-spot.index'));
        return redirect()->to($returnUrl)->with('success', 'Data blank spot berhasil diperbarui!');
    }

    /**
     * Admin - Delete blank spot
     */
    public function destroy($id)
    {
        $blankSpot = BlankSpot::findOrFail($id);

        if ($blankSpot->status_validasi === 'approved') {
            return redirect()->back()->with('error', '⚠️ Data yang sudah DISETUJUI (Approved) tidak dapat dihapus.');
        }

        $this->blankSpotService->delete($blankSpot, Auth::user());

        return redirect()->back()->with('success', 'Data blank spot berhasil dihapus.');
    }

    // ============================================================
    // USER (OPERATOR) METHODS
    // ============================================================

    /**
     * User - Index data blank spot (hanya milik Kabupaten sendiri)
     */
    public function userIndex(Request $request)
    {
        $user = Auth::user();

        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator'])
            ->where('kabupaten_id', $user->kabupaten_id);

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('status_validasi')) {
            $query->where('status_validasi', $request->status_validasi);
        }
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('kecamatan', fn($sq) => $sq->where('nama_kecamatan', 'like', "%{$s}%"))
                  ->orWhereHas('desa', fn($sq) => $sq->where('nama_desa', 'like', "%{$s}%"))
                  ->orWhere('nama_lokasi', 'like', "%{$s}%");
            });
        }

        $blankSpots = $query->latest()->paginate(10)->withQueryString();

        $tahuns = BlankSpot::where('kabupaten_id', $user->kabupaten_id)
            ->selectRaw('DISTINCT tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('user.blank-spot.index', compact('blankSpots', 'tahuns'));
    }

    /**
     * User - Form create blank spot
     */
    public function userCreate()
    {
        $user       = Auth::user();
        $kabupaten  = Kabupaten::find($user->kabupaten_id);
        $kecamatans = Kecamatan::where('kabupaten_id', $user->kabupaten_id)->orderBy('nama_kecamatan')->get();

        return view('user.blank-spot.create', compact('kabupaten', 'kecamatans'));
    }

    /**
     * User - Store blank spot
     */
    public function userStore(StoreBlankSpotRequest $request)
    {
        $user  = Auth::user();
        $data  = $request->validated();
        $photo = $request->file('foto');

        $inputKab = $request->input('kabupaten_id');
        if ($user->isOperator() && $inputKab !== null && (int) $inputKab !== (int) $user->kabupaten_id) {
            abort(403, 'Anda tidak memiliki otorisasi untuk menambah data pada Kabupaten/Kota lain.');
        }

        $data['kabupaten_id'] = $user->kabupaten_id;

        $this->blankSpotService->store($data, $user, $photo);

        return redirect()->back()
            ->with('success', 'Data berhasil dikirim dan masuk ke antrean validasi Admin Diskominfo.');
    }

    /**
     * User - Show detail blank spot (hanya milik kabupaten sendiri)
     */
    public function userShow($id)
    {
        $user = Auth::user();
        $blankSpot = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator', 'validator'])
            ->where('kabupaten_id', $user->kabupaten_id)
            ->findOrFail($id);

        return view('user.blank-spot.show', compact('blankSpot'));
    }

    /**
     * User - Edit blank spot (HANYA data berstatus rejected atau revisi/perlu_revisi)
     */
    public function userEdit($id)
    {
        $user      = Auth::user();
        $blankSpot = BlankSpot::where('kabupaten_id', $user->kabupaten_id)->findOrFail($id);

        if ($blankSpot->status_validasi === 'approved') {
            return redirect()->back()
                ->with('error', '⚠️ Data yang sudah disetujui (Approved) tidak bisa diedit.');
        }

        if ($blankSpot->status_validasi === 'pending') {
            return redirect()->back()
                ->with('error', '⚠️ Data yang masih berstatus Pending belum dapat diedit. Tunggu validasi dari Admin.');
        }

        if (!str_contains(url()->previous(), '/edit')) {
            session(['blank_spot_return_url' => url()->previous()]);
        }

        $kabupaten  = Kabupaten::find($user->kabupaten_id);
        $kecamatans = Kecamatan::where('kabupaten_id', $user->kabupaten_id)->orderBy('nama_kecamatan')->get();
        $desas      = Desa::where('kecamatan_id', $blankSpot->kecamatan_id)->orderBy('nama_desa')->get();

        return view('user.blank-spot.edit', compact('blankSpot', 'kabupaten', 'kecamatans', 'desas'));
    }

    /**
     * User - Update blank spot
     */
    public function userUpdate(UpdateBlankSpotRequest $request, $id)
    {
        $user      = Auth::user();
        $blankSpot = BlankSpot::where('kabupaten_id', $user->kabupaten_id)->findOrFail($id);

        if ($blankSpot->status_validasi === 'approved') {
            return redirect()->back()
                ->with('error', '⚠️ Data yang sudah disetujui (Approved) tidak dapat diubah.');
        }

        $data  = $request->validated();
        $photo = $request->file('foto');

        $data['kabupaten_id'] = $user->kabupaten_id;

        $this->blankSpotService->update($blankSpot, $data, $user, $photo);

        $returnUrl = session()->pull('blank_spot_return_url', route('user.blank-spot.index'));
        return redirect()->to($returnUrl)->with('success', 'Data berhasil diperbarui dan dikirim ulang untuk menunggu validasi Admin.');
    }

    /**
     * User - Delete blank spot
     */
    public function userDestroy($id)
    {
        $user      = Auth::user();
        $blankSpot = BlankSpot::where('kabupaten_id', $user->kabupaten_id)->findOrFail($id);

        if ($blankSpot->status_validasi === 'approved') {
            return redirect()->back()
                ->with('error', '⚠️ Data yang sudah disetujui (Approved) tidak dapat dihapus.');
        }

        $this->blankSpotService->delete($blankSpot, $user);

        return redirect()->back()->with('success', 'Data blank spot berhasil dihapus.');
    }
}