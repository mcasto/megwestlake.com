<?php

namespace App\Providers;

use App\Support\StorageHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Storage::macro('cleanupOldFiles', function (
            string $disk = 'public',
            string $path = 'upload-temp',
            int $olderThanHours = 24
        ) {
            return StorageHelper::cleanupOldFiles($disk, $path, $olderThanHours);
        });

        Storage::macro('siteImage', function (
            string $link = 'public',
        ) {
            return StorageHelper::siteImage($link);
        });

        Storage::macro('setImage', function (
            string $link = 'public',
            string $path
        ) {
            return StorageHelper::setImage($link, $path);
        });
    }
}
