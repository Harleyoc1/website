<?php

use App\Http\Middleware\IsAdminMiddleware;
use App\Livewire\Posts\CreatePost;
use App\Livewire\Posts\EditPost;
use App\Livewire\Posts\PostIndex;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::middleware(IsAdminMiddleware::class)->group(function () {
        Route::view('management', 'private.dashboard')->name('dashboard');
        Route::get('management/blog', PostIndex::class)->name('management.blog.index');
        Route::get('management/blog/create', CreatePost::class)->name('management.blog.create');
        Route::get('management/blog/edit/{slug}', EditPost::class)->name('management.blog.edit');
    });
});

require __DIR__.'/auth.php';
