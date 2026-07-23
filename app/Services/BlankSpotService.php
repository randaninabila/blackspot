<?php

namespace App\Services;

use App\Models\BlankSpot;
use App\Models\Desa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class BlankSpotService
{
    /**
     * Handle photo upload to Laravel Storage
     */
    public function uploadPhoto(UploadedFile $file, ?string $oldPath = null): string
    {
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return $file->store('blank-spots', 'public');
    }

    /**
     * Delete photo from storage if exists
     */
    public function deletePhoto(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Store a new Blank Spot record using DB::transaction()
     */
    public function store(array $data, User $user, ?UploadedFile $photoFile = null): BlankSpot
    {
        return DB::transaction(function () use ($data, $user, $photoFile) {
            // Resolve desa_id if nama_desa is passed
            if (empty($data['desa_id']) && !empty($data['nama_desa']) && !empty($data['kecamatan_id'])) {
                $desa = Desa::firstOrCreate([
                    'kecamatan_id' => $data['kecamatan_id'],
                    'nama_desa'    => trim($data['nama_desa']),
                ]);
                $data['desa_id'] = $desa->id;
            }
            unset($data['nama_desa']);

            // Handle photo upload
            if ($photoFile) {
                $data['foto'] = $this->uploadPhoto($photoFile);
            }

            // Set metadata
            $data['tahun'] = $data['tahun'] ?? now()->year;
            $data['status_validasi'] = 'pending';
            $data['created_by'] = $user->id;
            $data['validated_by'] = null;
            $data['validated_at'] = null;
            $data['catatan_revisi'] = null;

            $blankSpot = BlankSpot::create($data);

            // Record Audit Log
            AuditLogService::log("Menambah data Blank Spot ID: {$blankSpot->id} ({$user->nama})", request(), $user->id);

            return $blankSpot;
        });
    }

    /**
     * Update an existing Blank Spot record using DB::transaction()
     */
    public function update(BlankSpot $blankSpot, array $data, User $user, ?UploadedFile $photoFile = null): BlankSpot
    {
        return DB::transaction(function () use ($blankSpot, $data, $user, $photoFile) {
            // Resolve desa_id if nama_desa is passed
            if (empty($data['desa_id']) && !empty($data['nama_desa']) && !empty($data['kecamatan_id'])) {
                $desa = Desa::firstOrCreate([
                    'kecamatan_id' => $data['kecamatan_id'],
                    'nama_desa'    => trim($data['nama_desa']),
                ]);
                $data['desa_id'] = $desa->id;
            }
            unset($data['nama_desa']);

            // Handle photo update
            if ($photoFile) {
                $data['foto'] = $this->uploadPhoto($photoFile, $blankSpot->foto);
            }

            // If operator updates a rejected or revision item, reset status to pending for re-validation
            if ($user->isOperator()) {
                $data['status_validasi'] = 'pending';
                $data['validated_by'] = null;
                $data['validated_at'] = null;
            }

            $blankSpot->update($data);

            AuditLogService::log("Mengubah data Blank Spot ID: {$blankSpot->id} ({$user->nama})", request(), $user->id);

            return $blankSpot;
        });
    }

    /**
     * Delete a Blank Spot record
     */
    public function delete(BlankSpot $blankSpot, User $user): bool
    {
        return DB::transaction(function () use ($blankSpot, $user) {
            $id = $blankSpot->id;
            if ($blankSpot->foto) {
                $this->deletePhoto($blankSpot->foto);
            }

            $blankSpot->delete();

            AuditLogService::log("Menghapus data Blank Spot ID: {$id} ({$user->nama})", request(), $user->id);

            return true;
        });
    }
}
