<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupService
{
    /**
     * Backup Database SQL dump or JSON data
     */
    public function backupDatabase(User $user): string
    {
        $filename = 'backup-database-' . now()->format('Ymd-His') . '.json';
        $data = [
            'users'        => \App\Models\User::all(),
            'kabupaten'    => \App\Models\Kabupaten::all(),
            'kecamatan'    => \App\Models\Kecamatan::all(),
            'desa'         => \App\Models\Desa::all(),
            'blank_spots'  => \App\Models\BlankSpot::all(),
            'audit_logs'   => \App\Models\AuditLog::all(),
            'generated_at' => now()->toDateTimeString(),
        ];

        $content = json_encode($data, JSON_PRETTY_PRINT);
        Storage::disk('public')->put('backups/' . $filename, $content);

        AuditLogService::log("Melakukan Backup Database Backend ({$filename})", request(), $user->id);

        return Storage::disk('public')->path('backups/' . $filename);
    }

    /**
     * Backup storage photos to ZIP
     */
    public function backupStoragePhotos(User $user): string
    {
        $zipFilename = 'backup-photos-' . now()->format('Ymd-His') . '.zip';
        $zipPath = storage_path('app/public/backups/' . $zipFilename);

        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $files = Storage::disk('public')->allFiles('blank-spots');
            foreach ($files as $file) {
                $fullPath = storage_path('app/public/' . $file);
                if (file_exists($fullPath)) {
                    $zip->addFile($fullPath, basename($file));
                }
            }
            $zip->close();
        }

        AuditLogService::log("Melakukan Backup Storage Foto Backend ({$zipFilename})", request(), $user->id);

        return $zipPath;
    }
}
