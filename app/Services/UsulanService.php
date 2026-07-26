<?php

namespace App\Services;

use App\Models\UsulanBlankSpot;
use App\Models\BlankSpot;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UsulanService
{
    /**
     * Store new usulan bottom-up
     */
    public function store(array $data, ?User $user = null, ?UploadedFile $photo = null): UsulanBlankSpot
    {
        return DB::transaction(function () use ($data, $user, $photo) {
            if ($photo) {
                $data['foto'] = $photo->store('usulan-blank-spots', 'public');
            }

            $data['status_usulan'] = 'pending';
            $data['created_by']    = $user?->id;

            $usulan = UsulanBlankSpot::create($data);

            AuditLogService::log("Mengirim usulan bottom-up Blank Spot ID: {$usulan->id}", request(), $user?->id);

            return $usulan;
        });
    }

    /**
     * Approve usulan and convert to main BlankSpot record
     */
    public function approve(UsulanBlankSpot $usulan, User $admin): BlankSpot
    {
        return DB::transaction(function () use ($usulan, $admin) {
            $usulan->update(['status_usulan' => 'approved']);

            // Convert to main BlankSpot
            $blankSpot = BlankSpot::create([
                'kabupaten_id'    => $usulan->kabupaten_id,
                'kecamatan_id'    => $usulan->kecamatan_id,
                'desa_id'         => $usulan->desa_id,
                'nama_lokasi'     => $usulan->nama_lokasi,
                'latitude'        => $usulan->latitude,
                'longitude'       => $usulan->longitude,
                'radius'          => $usulan->radius,
                'keterangan'      => $usulan->keterangan,
                'foto'            => $usulan->foto,
                'tahun'           => now()->year,
                'semester'        => now()->month <= 6 ? 1 : 2,
                'status_validasi' => 'pending',
                'created_by'      => $admin->id,
            ]);

            AuditLogService::log("Menyetujui usulan bottom-up ID: {$usulan->id} dan mengonversi ke BlankSpot ID: {$blankSpot->id}", request(), $admin->id);

            return $blankSpot;
        });
    }

    /**
     * Reject usulan
     */
    public function reject(UsulanBlankSpot $usulan, User $admin): bool
    {
        return DB::transaction(function () use ($usulan, $admin) {
            $usulan->update(['status_usulan' => 'rejected']);

            AuditLogService::log("Menolak usulan bottom-up Blank Spot ID: {$usulan->id}", request(), $admin->id);

            return true;
        });
    }
}
