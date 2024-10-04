<?php

namespace App\Providers;

use App\Http\Middleware\VerifyClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use App\Http\Middleware\VerifyJWT;
use App\Http\Middleware\VerifyRecepcionist;
use App\Http\Middleware\VerifyStaff;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Router $router): void
    {
        $router->aliasMiddleware('jwt.verify', VerifyJWT::class);
        $router->aliasMiddleware('jwt.verify.recepcionist', VerifyRecepcionist::class);
        $router->aliasMiddleware('jwt.verify.staff', VerifyStaff::class);
        $router->aliasMiddleware('jwt.verify.client', VerifyClient::class);
    }
}
