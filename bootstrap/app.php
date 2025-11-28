<?php

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
        // Aquí volvemos a registrar tu middleware de seguridad por si acaso,
        // aunque en tu routes/web.php ya lo estás llamando.
        // Dejar esto limpio es seguro si usas la lógica de rutas que te di.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();