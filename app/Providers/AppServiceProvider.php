<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider {
    public function register(): void {}
    public function boot(): void {
        // Automatically attempt linking storage on shared hosting environments if missing
        if (!file_exists(public_path('storage')) && file_exists(storage_path('app/public'))) {
            @symlink(storage_path('app/public'), public_path('storage'));
        }
    }
}
