<?php

namespace App\Services;

use App\Models\BlankSpot;
use App\Models\BlankSpotPhoto;
use App\Models\BlankSpotHistory;
use App\Models\Desa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use DomainException;

class BlankSpotService
{
    /**
     * Handle photo upload to Laravel Storage (storage/app/public/blank-spots)
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
    public function store(array $data, User $user, ?UploadedFile $photoFile = null, array $additionalPhotos = []): BlankSpot
    {
        return DB::transaction(function () use ($data, $user, $photoFile, $additionalPhotos) {
            if ($user->isOperator()) {
                if (!empty($data['kabupaten_id']) && (int) $data['kabupaten_id'] !== (int) $user->kabupaten_id) {
                    throw new DomainException('Operator hanya diperbolehkan menambah data pada Kabupaten/Kota miliknya sendiri.');
                }
                $data['kabupaten_id'] = $user->kabupaten_id;
            }

            // Resolve desa_id (mendukung input ID numerik maupun string nama desa)
            $this->resolveDesa($data);

            // Handle main photo upload
            if ($photoFile) {
                $data['foto'] = $this->uploadPhoto($photoFile);
            }

            // Default values
            $data['tahun']           = $data['tahun'] ?? now()->year;
            $data['semester']        = $data['semester'] ?? (now()->month <= 6 ? 1 : 2);
            $data['status_validasi'] = $data['status_validasi'] ?? 'pending';
            $data['created_by']      = $user->id;

            $blankSpot = BlankSpot::create($data);

            // Handle multiple additional photos
            foreach ($additionalPhotos as $photo) {
                if ($photo instanceof UploadedFile) {
                    $path = $this->uploadPhoto($photo);
                    BlankSpotPhoto::create([
                        'blank_spot_id' => $blankSpot->id,
                        'filename'      => $photo->getClientOriginalName(),
                        'path'          => $path,
                        'uploaded_by'   => $user->id,
                    ]);
                }
            }

            // Record Audit Log
            AuditLogService::log("Menambah data Blank Spot ID: {$blankSpot->id} ({$user->nama})", request(), $user->id);

            return $blankSpot;
        });
    }

    /**
     * Update an existing Blank Spot record using DB::transaction() atomik
     */
    public function update(BlankSpot $blankSpot, array $data, User $user, ?UploadedFile $photoFile = null, array $additionalPhotos = []): BlankSpot
    {
        // BUSINESS RULE: Status Approved = LOCK TOTAL
        if ($blankSpot->status_validasi === 'approved') {
            throw new DomainException('Data yang sudah Disetujui (Approved) terkunci (LOCK) dan tidak dapat diubah.');
        }

        return DB::transaction(function () use ($blankSpot, $data, $user, $photoFile, $additionalPhotos) {
            // Snapshot data lama sebelum perubahan
            $oldData = $blankSpot->toArray();

            // Resolve desa_id (mendukung input ID numerik maupun string nama desa)
            $this->resolveDesa($data);

            // Handle photo update (replace & delete old file)
            if ($photoFile) {
                $data['foto'] = $this->uploadPhoto($photoFile, $blankSpot->foto);
            }

            // Operator update resets status to pending unless admin/verifikator
            if ($user->isOperator()) {
                $data['status_validasi'] = 'pending';
                $data['validated_by']    = null;
                $data['validated_at']    = null;
            }

            $blankSpot->update($data);
            $newData = $blankSpot->fresh()->toArray();

            // Handle multiple additional photos
            foreach ($additionalPhotos as $photo) {
                if ($photo instanceof UploadedFile) {
                    $path = $this->uploadPhoto($photo);
                    BlankSpotPhoto::create([
                        'blank_spot_id' => $blankSpot->id,
                        'filename'      => $photo->getClientOriginalName(),
                        'path'          => $path,
                        'uploaded_by'   => $user->id,
                    ]);
                }
            }

            // Record History Perubahan (Old Data vs New Data, User, Role, Timestamp)
            BlankSpotHistory::create([
                'blank_spot_id' => $blankSpot->id,
                'user_id'       => $user->id,
                'role'          => $user->role,
                'old_data'      => $oldData,
                'new_data'      => $newData,
                'created_at'    => now(),
            ]);

            // Record Audit Log
            AuditLogService::log("Mengubah data Blank Spot ID: {$blankSpot->id} ({$user->nama})", request(), $user->id);

            return $blankSpot;
        }    );
    }

    /**
     * Delete a Blank Spot record
     */
    public function delete(BlankSpot $blankSpot, User $user): bool
    {
        // BUSINESS RULE: Status Approved = LOCK TOTAL
        if ($blankSpot->status_validasi === 'approved') {
            throw new DomainException('Data yang sudah Disetujui (Approved) terkunci (LOCK) dan tidak dapat dihapus.');
        }

        return DB::transaction(function () use ($blankSpot, $user) {
            $id = $blankSpot->id;

            // Delete main photo
            if ($blankSpot->foto) {
                $this->deletePhoto($blankSpot->foto);
            }

            // Delete additional photos from storage
            foreach ($blankSpot->photos as $photo) {
                $this->deletePhoto($photo->path);
            }

            $blankSpot->delete();

            // Record Audit Log
            AuditLogService::log("Menghapus data Blank Spot ID: {$id} ({$user->nama})", request(), $user->id);

            return true;
        });
    }

    /**
     * Helper untuk menyelesaikan desa_id baik berupa numeric ID maupun string input manual
     */
    private function resolveDesa(array &$data): void
    {
        $namaDesaInput = $data['nama_desa'] ?? $data['desa'] ?? null;
        $kecamatanId   = $data['kecamatan_id'] ?? null;

        if (!empty($data['desa_id']) && !is_numeric($data['desa_id']) && $kecamatanId) {
            $desa = Desa::firstOrCreate([
                'kecamatan_id' => $kecamatanId,
                'nama_desa'    => trim($data['desa_id']),
            ]);
            $data['desa_id'] = $desa->id;
        } elseif (empty($data['desa_id']) && !empty($namaDesaInput) && $kecamatanId) {
            $desa = Desa::firstOrCreate([
                'kecamatan_id' => $kecamatanId,
                'nama_desa'    => trim($namaDesaInput),
            ]);
            $data['desa_id'] = $desa->id;
        }

        unset($data['nama_desa'], $data['desa']);
    }
}
