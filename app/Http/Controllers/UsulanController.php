<?php

namespace App\Http\Controllers;

use App\Models\UsulanBlankSpot;
use App\Http\Requests\StoreUsulanRequest;
use App\Services\UsulanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsulanController extends Controller
{
    protected UsulanService $usulanService;

    public function __construct(UsulanService $usulanService)
    {
        $this->usulanService = $usulanService;
    }

    /**
     * Submit usulan bottom-up baru
     */
    public function store(StoreUsulanRequest $request)
    {
        $user  = Auth::user();
        $photo = $request->file('foto');

        $usulan = $this->usulanService->store($request->validated(), $user, $photo);

        return response()->json([
            'success' => true,
            'message' => 'Usulan lokasi blank spot berhasil dikirim!',
            'data'    => $usulan,
        ]);
    }

    /**
     * Approve usulan (Admin only)
     */
    public function approve($id)
    {
        $usulan = UsulanBlankSpot::findOrFail($id);
        $admin  = Auth::user();

        $blankSpot = $this->usulanService->approve($usulan, $admin);

        return response()->json([
            'success' => true,
            'message' => 'Usulan disetujui dan masuk ke antrean utama blank spot!',
            'data'    => $blankSpot,
        ]);
    }

    /**
     * Reject usulan (Admin only)
     */
    public function reject($id)
    {
        $usulan = UsulanBlankSpot::findOrFail($id);
        $admin  = Auth::user();

        $this->usulanService->reject($usulan, $admin);

        return response()->json([
            'success' => true,
            'message' => 'Usulan berhasil ditolak!',
        ]);
    }
}
