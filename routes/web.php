<?php

use App\Http\Middleware\IsAdminMiddleware;
use App\Livewire\Blog\BlogIndex;
use App\Livewire\Blog\ShowPost;
use App\Livewire\Posts\CreatePost;
use App\Livewire\Posts\EditPost;
use App\Livewire\Posts\PostIndex;
use App\Livewire\Projects\CreateProject;
use App\Livewire\Projects\EditProject;
use App\Livewire\Projects\ProjectIndex;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('blog', BlogIndex::class)->name('blog.index');
Route::get('blog/{slug}', ShowPost::class)->name('blog.show');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::middleware(IsAdminMiddleware::class)->group(function () {
        Route::view('management', 'private.dashboard')->name('dashboard');
        Route::get('management/portfolio', ProjectIndex::class)->name('management.portfolio.index');
        Route::get('management/portfolio/create', CreateProject::class)->name('management.portfolio.create');
        Route::get('management/portfolio/edit/{slug}', EditProject::class)->name('management.portfolio.edit');
        Route::get('management/blog', PostIndex::class)->name('management.blog.index');
        Route::get('management/blog/create', CreatePost::class)->name('management.blog.create');
        Route::get('management/blog/edit/{slug}', EditPost::class)->name('management.blog.edit');
    });
});

require __DIR__.'/auth.php';
