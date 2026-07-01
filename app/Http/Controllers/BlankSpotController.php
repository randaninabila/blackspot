<?php

namespace App\Http\Controllers;

use App\Models\BlankSpot;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlankSpotController extends Controller
{
    /**
     * API get kecamatan berdasarkan kabupaten_id
     */
    public function getKecamatan($kabupaten_id)
    {
        $kecamatans = Kecamatan::where('kabupaten_id', $kabupaten_id)
            ->orderBy('nama_kecamatan')
            ->get(['id', 'nama_kecamatan']);
        
        return response()->json($kecamatans);
    }

    /**
     * API get desa berdasarkan kecamatan_id
     */
    public function getDesa($kecamatan_id)
    {
        $desas = Desa::where('kecamatan_id', $kecamatan_id)
            ->orderBy('nama_desa')
            ->get(['id', 'nama_desa']);
        
        return response()->json($desas);
    }

    /**
     * Admin - Index data blank spot
     */
    public function adminIndex(Request $request)
    {
        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator']);

        if ($request->kabupaten_id) {
            $query->where('kabupaten_id', $request->kabupaten_id);
        }
        if ($request->status_validasi) {
            $query->where('status_validasi', $request->status_validasi);
        }
        if ($request->tahun) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('kabupaten', fn($sq) => $sq->where('nama_kabupaten', 'like', "%$s%"))
                  ->orWhereHas('kecamatan', fn($sq) => $sq->where('nama_kecamatan', 'like', "%$s%"))
                  ->orWhereHas('desa', fn($sq) => $sq->where('nama_desa', 'like', "%$s%"));
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kabupaten_id' => 'required|exists:kabupaten,id',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'nama_desa'    => 'required|string|max:255',
            'latitude'     => 'required|numeric|between:-90,90',
            'longitude'    => 'required|numeric|between:-180,180',
            'tahun'        => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'keterangan'   => 'nullable|string|max:1000',
        ]);

        $desa = Desa::firstOrCreate([
            'kecamatan_id' => $validated['kecamatan_id'],
            'nama_desa' => $validated['nama_desa'],
        ]);

        $validated['desa_id'] = $desa->id;
        $validated['status_validasi'] = 'pending';
        $validated['created_by'] = Auth::id();
        $validated['validated_by'] = null;
        $validated['validated_at'] = null;

        unset($validated['nama_desa']);

        $bs = BlankSpot::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Admin menambah data blank spot ID: ' . $bs->id,
            'waktu' => now(),
        ]);

        return redirect()->route('admin.add')->with('success', 'Data blank spot berhasil ditambahkan!');
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
        $blankSpot  = BlankSpot::findOrFail($id);
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        $kecamatans = Kecamatan::where('kabupaten_id', $blankSpot->kabupaten_id)->orderBy('nama_kecamatan')->get();
        $desas      = Desa::where('kecamatan_id', $blankSpot->kecamatan_id)->orderBy('nama_desa')->get();
        
        return view('admin.blank-spot.edit', compact('blankSpot', 'kabupatens', 'kecamatans', 'desas'));
    }

    /**
     * Admin - Update blank spot
     */
    public function update(Request $request, $id)
    {
        $blankSpot = BlankSpot::findOrFail($id);

        $validated = $request->validate([
            'kabupaten_id'   => 'required|exists:kabupaten,id',
            'kecamatan_id'   => 'required|exists:kecamatan,id',
            'desa_id'        => 'required|exists:desa,id',
            'latitude'       => 'required|numeric|between:-90,90',
            'longitude'      => 'required|numeric|between:-180,180',
            'tahun'          => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'keterangan'     => 'nullable|string|max:1000',
            'status_validasi' => 'required|in:pending,approved,rejected',
        ]);

        $blankSpot->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Admin mengedit blank spot ID: ' . $id,
            'waktu' => now()
        ]);

        return redirect()->route('admin.add')->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Admin - Delete blank spot
     */
    public function destroy($id)
    {
        $blankSpot = BlankSpot::findOrFail($id);
        $blankSpot->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Admin menghapus blank spot ID: ' . $id,
            'waktu' => now()
        ]);

        return redirect()->route('admin.add')->with('success', 'Data berhasil dihapus.');
    }

    // ============================================================
    // USER (OPERATOR) METHODS
    // ============================================================

    /**
     * User - Index data blank spot (hanya milik sendiri)
     */
  public function userIndex(Request $request)
{
    $user = Auth::user();
    $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
        ->where('created_by', $user->id);  // ← HANYA data milik user ini

    // ... filter ...

    $blankSpots = $query->latest()->paginate(10)->withQueryString();
    $tahuns = BlankSpot::where('created_by', $user->id)
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
public function userStore(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'kecamatan_id' => 'required|exists:kecamatan,id',
        'nama_desa'    => 'required|string|max:255',
        'latitude'     => 'required|numeric|between:-90,90',
        'longitude'    => 'required|numeric|between:-180,180',
        'tahun'        => 'required|integer|min:2000|max:' . (date('Y') + 1),
        'keterangan'   => 'nullable|string|max:1000',
    ]);

    // Cari atau buat desa baru
    $desa = Desa::firstOrCreate([
        'kecamatan_id' => $validated['kecamatan_id'],
        'nama_desa' => $validated['nama_desa'],
    ]);

    // Siapkan data untuk insert
    $data = [
        'kabupaten_id' => $user->kabupaten_id,
        'kecamatan_id' => $validated['kecamatan_id'],
        'desa_id' => $desa->id,
        'latitude' => $validated['latitude'],
        'longitude' => $validated['longitude'],
        'tahun' => $validated['tahun'],
        'keterangan' => $validated['keterangan'] ?? null,
        'status_validasi' => 'pending',
        'created_by' => $user->id,
        'validated_by' => null,
        'validated_at' => null,
    ];

    $bs = BlankSpot::create($data);

    AuditLog::create([
        'user_id' => $user->id,
        'aktivitas' => 'Operator menambah data blank spot ID: ' . $bs->id,
        'waktu' => now(),
    ]);

    // Flash notifikasi untuk admin
    session()->flash('notifikasi', 'Ada data baru menunggu validasi!');

    return redirect()->route('user.blank-spot.index')->with('success', 'Data berhasil dikirim dan menunggu validasi admin.');
}

    /**
     * User - Show detail blank spot
     */
    public function userShow($id)
    {
        $blankSpot = BlankSpot::with(['kabupaten', 'kecamatan', 'desa'])
            ->where('created_by', Auth::id())
            ->findOrFail($id);
        
        return view('user.blank-spot.show', compact('blankSpot'));
    }

    /**
     * User - Edit blank spot
     */
    public function userEdit($id)
    {
        $user      = Auth::user();
        $blankSpot = BlankSpot::where('created_by', $user->id)->findOrFail($id);

        if ($blankSpot->status_validasi === 'approved') {
            return redirect()->route('user.blank-spot.index')->with('error', 'Data yang sudah disetujui tidak bisa diedit.');
        }

        $kabupaten  = Kabupaten::find($user->kabupaten_id);
        $kecamatans = Kecamatan::where('kabupaten_id', $user->kabupaten_id)->orderBy('nama_kecamatan')->get();
        $desas      = Desa::where('kecamatan_id', $blankSpot->kecamatan_id)->orderBy('nama_desa')->get();

        return view('user.blank-spot.edit', compact('blankSpot', 'kabupaten', 'kecamatans', 'desas'));
    }

    /**
     * User - Update blank spot
     */
    public function userUpdate(Request $request, $id)
    {
        $user      = Auth::user();
        $blankSpot = BlankSpot::where('created_by', $user->id)->findOrFail($id);

        if ($blankSpot->status_validasi === 'approved') {
            return redirect()->route('user.blank-spot.index')->with('error', 'Data yang sudah disetujui tidak bisa diedit.');
        }

        $validated = $request->validate([
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'desa_id'      => 'required|exists:desa,id',
            'latitude'     => 'required|numeric|between:-90,90',
            'longitude'    => 'required|numeric|between:-180,180',
            'tahun'        => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'keterangan'   => 'nullable|string|max:1000',
        ]);

        $validated['status_validasi'] = 'pending';
        $blankSpot->update($validated);

        AuditLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Operator mengedit blank spot ID: ' . $id,
            'waktu' => now()
        ]);

        return redirect()->route('user.blank-spot.index')->with('success', 'Data diperbarui dan menunggu validasi ulang.');
    }

    /**
     * User - Delete blank spot
     */
    public function userDestroy($id)
    {
        $blankSpot = BlankSpot::where('created_by', Auth::id())->findOrFail($id);

        if ($blankSpot->status_validasi === 'approved') {
            return redirect()->route('user.blank-spot.index')->with('error', 'Data yang sudah disetujui tidak bisa dihapus.');
        }

        $blankSpot->delete();
        
        return redirect()->route('user.blank-spot.index')->with('success', 'Data berhasil dihapus.');
    }
}