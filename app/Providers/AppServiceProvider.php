<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


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
    public function boot()
    {
        View::composer('*', function ($view) {
            $user = auth()->user();

            $menu = [];

            if ($user && $user->hasRole('admin')) {
                $menu = include config_path('menu/admin.php');
            } elseif ($user && $user->hasRole('user')) {
                $menu = include config_path('menu/user.php');
            }

            config(['adminlte.menu' => $menu]);
        });
    }

}
