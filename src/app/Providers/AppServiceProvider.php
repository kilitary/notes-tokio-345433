<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Models\Token;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Sanctum::usePersonalAccessTokenModel(Token::class);
    }
}

