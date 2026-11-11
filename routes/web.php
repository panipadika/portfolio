<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\CredentialController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\MediaImageController;
use App\Http\Controllers\PublicStorageController;
use App\Http\Middleware\EnsureDirectBladeVisit;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::inertia('/', 'welcome')->name('home');

Route::get('admin', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    $customSlug = SiteSetting::get('admin_login_slug', 'admin');
    if ($customSlug !== 'admin') {
        return redirect($customSlug);
    }

    return redirect()->route('login');
});

Route::middleware(['auth', 'verified', EnsureDirectBladeVisit::class])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('dashboard')->name('admin.')->group(function () {
        Route::resource('projects', ProjectController::class);
        Route::resource('experiences', ExperienceController::class);
        Route::get('credentials/lookup-icon', [CredentialController::class, 'lookupIcon'])->name('credentials.lookup-icon');
        Route::post('credentials/quick-tech-stack', [CredentialController::class, 'quickStoreTechStack'])->name('credentials.quick-tech-stack');
        Route::resource('credentials', CredentialController::class);
        Route::resource('messages', MessageController::class)->only(['index', 'show', 'destroy']);
        Route::patch('messages/{message}/toggle-read', [MessageController::class, 'toggleRead'])->name('messages.toggle-read');
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('account', [AccountController::class, 'index'])->name('account.index');
        Route::put('account/profile', [AccountController::class, 'updateProfile'])->name('account.profile');
        Route::put('account/password', [AccountController::class, 'updatePassword'])->name('account.password');
        Route::put('account/login-slug', [AccountController::class, 'updateLoginSlug'])->name('account.login-slug');
    });
});

Route::get('media-opt/{width}/{path}', MediaImageController::class)
    ->withoutMiddleware('web')
    ->where('width', '128|640|960|1280')
    ->where('path', '.*')
    ->name('media.opt');

Route::get('storage/{path}', PublicStorageController::class)
    ->withoutMiddleware('web')
    ->where('path', '.*')
    ->name('storage.fallback');

require __DIR__.'/settings.php';

Route::get('{slug}', function (Request $request, string $slug) {
    $customSlug = SiteSetting::get('admin_login_slug', 'admin');

    if ($slug !== $customSlug) {
        abort(404);
    }

    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return app(AuthenticatedSessionController::class)->create($request);
})->where('slug', '[a-zA-Z0-9\-_]+');
