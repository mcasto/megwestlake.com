<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class StorageHelper
{
    /**
     * Clean up old files from a storage directory
     *
     * @param string $disk The storage disk name
     * @param string $path The directory path
     * @param int $olderThanHours Files older than this many hours will be deleted
     * @return int Number of files deleted
     */
    public static function cleanupOldFiles(
        string $disk = 'public',
        string $path = 'upload-temp',
        int $olderThanHours = 24
    ): int {
        $deletedCount = 0;
        $files = Storage::disk($disk)->files($path);
        $cutoffTime = Carbon::now()->subHours($olderThanHours)->timestamp;

        foreach ($files as $file) {
            $lastModified = Storage::disk($disk)->lastModified($file);

            if ($lastModified < $cutoffTime) {
                Storage::disk($disk)->delete($file);
                $deletedCount++;
            }
        }

        return $deletedCount;
    }
}
