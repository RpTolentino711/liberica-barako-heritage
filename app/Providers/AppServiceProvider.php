<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider {
    public function register(): void {}
    public function boot(): void {
        $publicStorage = public_path('storage');
        $appPublic = storage_path('app/public');
        
        // Remove broken symlink if pointing to invalid destination
        if (is_link($publicStorage) && !file_exists($publicStorage)) {
            @unlink($publicStorage);
        }

        // Automatically link storage on shared hosting environments if missing
        if (!file_exists($publicStorage) && file_exists($appPublic)) {
            @symlink($appPublic, $publicStorage);
        }
    }
}
