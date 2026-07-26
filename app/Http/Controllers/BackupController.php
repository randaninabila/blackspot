<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BackupController extends Controller
{
    protected BackupService $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Download backup database JSON/SQL
     */
    public function backupDb()
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Admin yang diizinkan mem-backup database.');
        }

        $path = $this->backupService->backupDatabase($user);
        return response()->download($path);
    }

    /**
     * Download backup storage foto ZIP
     */
    public function backupPhotos()
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Admin yang diizinkan mem-backup foto storage.');
        }

        $zipPath = $this->backupService->backupStoragePhotos($user);
        return response()->download($zipPath);
    }
}
