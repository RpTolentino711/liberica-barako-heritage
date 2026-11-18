<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPasswordResetController;
use App\Http\Controllers\AdminEmailCheckController;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class,'index'])->name('home');
Route::post('/contact',[HomeController::class,'message'])->middleware('throttle:6,1')->name('contact.store');
Route::get('/admin/login',[AdminController::class,'login'])->name('admin.login');
Route::post('/admin/login',[AdminController::class,'authenticate'])->middleware('throttle:5,1')->name('admin.authenticate');
// Pre-login email status: restrict requests to reduce account discovery.
Route::post('/admin/check-email', AdminEmailCheckController::class)->middleware('throttle:5,1')->name('admin.check-email');
Route::post('/admin/logout',[AdminController::class,'logout'])->middleware('auth')->name('admin.logout');

// Administrator-only password recovery (guest-accessible, rate limited).
Route::get('/admin/forgot-password', [AdminPasswordResetController::class, 'requestForm'])->name('admin.password.request');
Route::post('/admin/forgot-password', [AdminPasswordResetController::class, 'sendLink'])->middleware('throttle:3,1')->name('admin.password.email');
// Laravel's default ResetPassword notification expects this exact route name.
Route::get('/admin/reset-password/{token}', [AdminPasswordResetController::class, 'resetForm'])->name('password.reset');
Route::post('/admin/reset-password', [AdminPasswordResetController::class, 'reset'])->middleware('throttle:5,1')->name('admin.password.update');


Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/',[AdminController::class,'dashboard'])->name('dashboard');
    Route::get('/articles',[AdminController::class,'articles'])->name('articles');
    Route::get('/articles/new',[AdminController::class,'articleForm'])->name('articles.new');
    Route::post('/articles',[AdminController::class,'saveArticle'])->name('articles.store');
    Route::get('/articles/{article}/edit',[AdminController::class,'articleForm'])->name('articles.edit');
    Route::put('/articles/{article}',[AdminController::class,'saveArticle'])->name('articles.update');
    Route::delete('/articles/{article}',[AdminController::class,'deleteArticle'])->name('articles.delete');
    Route::get('/gallery',[AdminController::class,'gallery'])->name('gallery');
    Route::post('/gallery',[AdminController::class,'saveGallery'])->name('gallery.store');
    Route::delete('/gallery/{item}',[AdminController::class,'deleteGallery'])->name('gallery.delete');
    Route::get('/references',[AdminController::class,'references'])->name('references');
    Route::post('/references',[AdminController::class,'saveReference'])->name('references.store');
    Route::delete('/references/{reference}',[AdminController::class,'deleteReference'])->name('references.delete');
    Route::get('/messages',[AdminController::class,'messages'])->name('messages');
    Route::patch('/messages/{message}',[AdminController::class,'updateMessage'])->name('messages.update');
    Route::get('/settings',[AdminController::class,'settings'])->name('settings');
    Route::put('/settings',[AdminController::class,'saveSettings'])->name('settings.save');
    Route::get('/link-storage', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('storage:link');
            return back()->with('ok', 'Storage linked successfully: ' . \Illuminate\Support\Facades\Artisan::output());
        } catch (\Throwable $e) {
            return back()->withErrors(['storage' => 'Could not link storage: ' . $e->getMessage()]);
        }
    })->name('storage.link');
});

Route::get('/check-storage', function () {
    $publicStorage = public_path('storage');
    $appPublic = storage_path('app/public');
    
    // Auto-create symlink if missing
    if (!file_exists($publicStorage) && file_exists($appPublic)) {
        @symlink($appPublic, $publicStorage);
    }
    
    return response()->json([
        'public_storage_exists' => file_exists($publicStorage),
        'public_storage_is_link' => is_link($publicStorage),
        'app_public_exists' => file_exists($appPublic),
        'files_in_app_public' => file_exists($appPublic) ? scandir($appPublic) : [],
        'files_in_gallery' => file_exists($appPublic . '/gallery') ? scandir($appPublic . '/gallery') : [],
        'files_in_articles' => file_exists($appPublic . '/articles') ? scandir($appPublic . '/articles') : [],
    ]);
});

// Secure shared-hosting storage fallback route
Route::get('/storage/{path}', function (string $path) {
    $basePath = realpath(storage_path('app/public'));
    $targetPath = realpath(storage_path('app/public/' . $path));

    if (!$targetPath || !$basePath || !str_starts_with($targetPath, $basePath) || !is_file($targetPath)) {
        abort(404);
    }

    return response()->file($targetPath);
})->where('path', '.*')->name('storage.fallback');

Route::get('/public/storage/{path}', function (string $path) {
    return redirect('/storage/' . $path);
})->where('path', '.*');

