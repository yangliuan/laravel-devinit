<?php

namespace App\Providers;

use App\Http\Middleware\AdminRBAC;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Carbon::setLocale('zh');
        JsonResource::withoutWrapping();
        $this->app->singleton(AdminRBAC::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}