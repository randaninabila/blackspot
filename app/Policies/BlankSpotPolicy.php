<?php

namespace App\Policies;

use App\Models\BlankSpot;
use App\Models\User;

class BlankSpotPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isOperator() || $user->isVerifikator();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BlankSpot $blankSpot): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Admin, Operator, dan Verifikator dapat melihat seluruh data (read-only)
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ?int $targetKabupatenId = null): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isOperator()) {
            if ($targetKabupatenId !== null) {
                return (int) $user->kabupaten_id === (int) $targetKabupatenId;
            }
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BlankSpot $blankSpot): bool
    {
        // RULE PENTING: Data yang sudah Disetujui (Approved) TOTAL LOCK! Tidak bisa diedit siapapun.
        if ($blankSpot->status_validasi === 'approved') {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isOperator()) {
            // Operator hanya boleh mengedit jika kabupaten sesuai AND status in Draft, Ditolak, Perlu Revisi
            $isSameKabupaten = (int) $user->kabupaten_id === (int) $blankSpot->kabupaten_id;
            $allowedStatus   = in_array($blankSpot->status_validasi, ['draft', 'pending', 'rejected', 'revisi', 'perlu_revisi']);

            return $isSameKabupaten && $allowedStatus;
        }

        if ($user->isVerifikator()) {
            return (int) $user->kabupaten_id === (int) $blankSpot->kabupaten_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BlankSpot $blankSpot): bool
    {
        // RULE PENTING: Data yang sudah Disetujui (Approved) TOTAL LOCK! Tidak bisa dihapus siapapun.
        if ($blankSpot->status_validasi === 'approved') {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isOperator()) {
            return (int) $user->kabupaten_id === (int) $blankSpot->kabupaten_id;
        }

        return false;
    }

    /**
     * Determine whether the user can export data.
     */
    public function export(User $user, ?int $targetKabupatenId = null): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($targetKabupatenId !== null) {
            return (int) $user->kabupaten_id === (int) $targetKabupatenId;
        }

        return $user->isOperator() || $user->isVerifikator();
    }
}
