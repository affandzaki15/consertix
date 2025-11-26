<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Route middleware.
     */
    protected $routeMiddleware = [
        'auth'   => \App\Http\Middleware\Authenticate::class,
        'admin'  => \App\Http\Middleware\AdminMiddleware::class,
        // tambahkan middleware lain jika perlu
    ];
}