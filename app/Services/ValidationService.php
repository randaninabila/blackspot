<?php

namespace App\Services;

use App\Models\BlankSpot;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ValidationService
{
    /**
     * Approve a Blank Spot entry
     */
    public function approve(BlankSpot $blankSpot, User $admin): BlankSpot
    {
        // Business Rule: Admin cannot approve invalid coordinates
        if ($blankSpot->latitude < -90 || $blankSpot->latitude > 90 || $blankSpot->longitude < -180 || $blankSpot->longitude > 180) {
            throw new InvalidArgumentException('Koordinat Latitude/Longitude tidak valid untuk disetujui.');
        }

        return DB::transaction(function () use ($blankSpot, $admin) {
            $blankSpot->update([
                'status_validasi' => 'approved',
                'validated_by'    => $admin->id,
                'validated_at'    => now(),
            ]);

            AuditLogService::log("Menyetujui (Approve) data Blank Spot ID: {$blankSpot->id}", request(), $admin->id);

            return $blankSpot;
        });
    }

    /**
     * Reject a Blank Spot entry
     */
    public function reject(BlankSpot $blankSpot, User $admin, ?string $reason = null): BlankSpot
    {
        return DB::transaction(function () use ($blankSpot, $admin, $reason) {
            $blankSpot->update([
                'status_validasi' => 'rejected',
                'catatan_revisi'  => $reason ?? $blankSpot->catatan_revisi,
                'validated_by'    => $admin->id,
                'validated_at'    => now(),
            ]);

            AuditLogService::log("Menolak (Reject) data Blank Spot ID: {$blankSpot->id}", request(), $admin->id);

            return $blankSpot;
        });
    }

    /**
     * Return a Blank Spot entry for revision (Perlu Revisi)
     */
    public function requestRevision(BlankSpot $blankSpot, User $admin, string $revisionNote): BlankSpot
    {
        if (empty(trim($revisionNote))) {
            throw new InvalidArgumentException('Catatan / Alasan revisi wajib diisi.');
        }

        return DB::transaction(function () use ($blankSpot, $admin, $revisionNote) {
            $blankSpot->update([
                'status_validasi' => 'revisi',
                'catatan_revisi'  => trim($revisionNote),
                'validated_by'    => $admin->id,
                'validated_at'    => now(),
            ]);

            AuditLogService::log("Mengembalikan data Blank Spot ID: {$blankSpot->id} untuk Revisi. Catatan: {$revisionNote}", request(), $admin->id);

            return $blankSpot;
        });
    }

    /**
     * Mass approve entries
     */
    public function massApprove(array $ids, User $admin): int
    {
        return DB::transaction(function () use ($ids, $admin) {
            $count = BlankSpot::whereIn('id', $ids)->update([
                'status_validasi' => 'approved',
                'validated_by'    => $admin->id,
                'validated_at'    => now(),
            ]);

            AuditLogService::log("Validasi Massal: Menyetujui {$count} data Blank Spot", request(), $admin->id);

            return $count;
        });
    }

    /**
     * Mass reject entries
     */
    public function massReject(array $ids, User $admin): int
    {
        return DB::transaction(function () use ($ids, $admin) {
            $count = BlankSpot::whereIn('id', $ids)->update([
                'status_validasi' => 'rejected',
                'validated_by'    => $admin->id,
                'validated_at'    => now(),
            ]);

            AuditLogService::log("Validasi Massal: Menolak {$count} data Blank Spot", request(), $admin->id);

            return $count;
        });
    }
}
