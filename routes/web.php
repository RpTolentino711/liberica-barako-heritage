<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPasswordResetController;
use App\Http\Controllers\AdminEmailCheckController;
use App\Http\Controllers\AdminEncyclopediaController;
use App\Http\Controllers\AdminJourneyStepController;
use App\Http\Controllers\EncyclopediaController;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/encyclopedia', [EncyclopediaController::class, 'index'])->name('encyclopedia.index');
Route::get('/encyclopedia/references/{reference}/document.pdf', [EncyclopediaController::class, 'pdf'])
    ->whereNumber('reference')->name('encyclopedia.reference.pdf');
Route::get('/encyclopedia/{topicSlug}/{entrySlug}', [EncyclopediaController::class, 'entry'])->name('encyclopedia.entry');
Route::get('/encyclopedia/{topicSlug}', [EncyclopediaController::class, 'topic'])->name('encyclopedia.topic');
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
    Route::get('/encyclopedia', [AdminEncyclopediaController::class, 'index'])->name('encyclopedia');
    Route::get('/bean-to-cup', [AdminJourneyStepController::class, 'index'])->name('journey.index');
    Route::patch('/bean-to-cup/reorder', [AdminJourneyStepController::class, 'reorder'])->name('journey.reorder');
    Route::get('/bean-to-cup/new', [AdminJourneyStepController::class, 'create'])->name('journey.create');
    Route::post('/bean-to-cup', [AdminJourneyStepController::class, 'save'])->name('journey.store');
    Route::get('/bean-to-cup/{step}/edit', [AdminJourneyStepController::class, 'edit'])->name('journey.edit');
    Route::put('/bean-to-cup/{step}', [AdminJourneyStepController::class, 'save'])->name('journey.update');
    Route::delete('/bean-to-cup/{step}', [AdminJourneyStepController::class, 'delete'])->name('journey.delete');
    Route::get('/encyclopedia/topics/new', [AdminEncyclopediaController::class, 'createTopic'])->name('encyclopedia.topics.new');
    Route::post('/encyclopedia/topics', [AdminEncyclopediaController::class, 'saveTopic'])->name('encyclopedia.topics.store');
    Route::get('/encyclopedia/topics/{topic}/edit', [AdminEncyclopediaController::class, 'editTopic'])->name('encyclopedia.topics.edit');
    Route::put('/encyclopedia/topics/{topic}', [AdminEncyclopediaController::class, 'saveTopic'])->name('encyclopedia.topics.update');
    Route::delete('/encyclopedia/topics/{topic}', [AdminEncyclopediaController::class, 'deleteTopic'])->name('encyclopedia.topics.delete');
    Route::get('/encyclopedia/topics/{topic}/entries', [AdminEncyclopediaController::class, 'entries'])->name('encyclopedia.entries');
    Route::get('/encyclopedia/topics/{topic}/entries/new', [AdminEncyclopediaController::class, 'createEntry'])->name('encyclopedia.entries.new');
    Route::post('/encyclopedia/topics/{topic}/entries', [AdminEncyclopediaController::class, 'saveEntry'])->name('encyclopedia.entries.store');
    Route::get('/encyclopedia/topics/{topic}/entries/{entry}/edit', [AdminEncyclopediaController::class, 'editEntry'])->scopeBindings()->name('encyclopedia.entries.edit');
    Route::put('/encyclopedia/topics/{topic}/entries/{entry}', [AdminEncyclopediaController::class, 'saveEntry'])->scopeBindings()->name('encyclopedia.entries.update');
    Route::delete('/encyclopedia/topics/{topic}/entries/{entry}', [AdminEncyclopediaController::class, 'deleteEntry'])->scopeBindings()->name('encyclopedia.entries.delete');
    Route::patch('/encyclopedia/topics/{topic}/entries/{entry}/publication', [AdminEncyclopediaController::class, 'toggleEntryPublication'])->scopeBindings()->name('encyclopedia.entries.publication');
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
    
    $linkTarget = is_link($publicStorage) ? readlink($publicStorage) : null;
    
    // If the symlink is broken, remove it and create a correct one
    if (is_link($publicStorage) && !file_exists($publicStorage)) {
        @unlink($publicStorage);
    }
    
    if (!file_exists($publicStorage) && file_exists($appPublic)) {
        @symlink($appPublic, $publicStorage);
    }
    
    return response()->json([
        'public_storage_exists' => file_exists($publicStorage),
        'public_storage_is_link' => is_link($publicStorage),
        'old_link_target' => $linkTarget,
        'current_link_target' => is_link($publicStorage) ? readlink($publicStorage) : null,
        'app_public_path' => $appPublic,
        'app_public_exists' => file_exists($appPublic),
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

