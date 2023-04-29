<?php

namespace App\Providers;

use App\Cart\Cart;
use App\Models\User;
use Illuminate\Auth\TokenGuard;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Guard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Cart::class, function ($app) {

            $user = $app->auth->user();
            return new Cart($user);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
