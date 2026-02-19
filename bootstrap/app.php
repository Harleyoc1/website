<?php

use App\Http\Middleware\AdminAuthorization;
use App\Http\Middleware\BasicAuthorization;
use App\Http\Middleware\MavenEditorAuthorization;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'maven/*'
        ])->alias([
            'basic_auth' => BasicAuthorization::class,
            'maven_editor_auth' => MavenEditorAuthorization::class,
            'admin_auth' => AdminAuthorization::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
