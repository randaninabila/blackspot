<?php

namespace App\Http\Controllers;

use App\Models\BlankSpot;
use App\Http\Requests\VerifikasiRequest;
use App\Services\ValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiController extends Controller
{
    protected ValidationService $validationService;

    public function __construct(ValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    /**
     * Tampilkan data untuk Verifikator Kabupaten
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $this->authorize('viewAny', BlankSpot::class);

        $query = BlankSpot::with(['kabupaten', 'kecamatan', 'desa', 'creator']);

        if ($user->isVerifikator()) {
            $query->where('kabupaten_id', $user->kabupaten_id);
        }

        if ($request->filled('status')) {
            $query->where('status_validasi', $request->status);
        }

        $blankSpots = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.validasi.index', compact('blankSpots'));
    }

    /**
     * Proses Verifikasi Lapangan oleh Verifikator
     */
    public function verify(VerifikasiRequest $request, $id)
    {
        $blankSpot = BlankSpot::findOrFail($id);
        $this->authorize('update', $blankSpot);

        $user = Auth::user();
        $this->validationService->verifyField($blankSpot, $user, $request->validated());

        return redirect()->back()->with('success', 'Verifikasi lapangan berhasil dicatat!');
    }
}
