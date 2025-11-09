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

    public static function siteImage(string $link)
    {
        $config = json_decode(Storage::disk('local')
            ->get('/app/config.json'));

        $image = collect($config->dashboardImages)
            ->where('link', $link)
            ->first();

        return $image;
        // return collect($config->dashboardImages)->first(fn($item) => $item->link == $link);
    }

    public static function setImage(string $link, string $path): bool
    {
        $configPath = '/app/config.json';
        $config = json_decode(Storage::disk('local')->get($configPath));

        // Find and update the image for the given link
        $updated = false;
        foreach ($config->dashboardImages as $image) {
            if ($image->link === $link) {
                $image->image = $path;
                $updated = true;
                break;
            }
        }

        // Save the updated config back to the file
        if ($updated) {
            Storage::disk('local')->put(
                $configPath,
                json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
        }

        return $updated;
    }
}
