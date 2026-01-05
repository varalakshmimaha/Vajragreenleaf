<?php

namespace App\Providers;

use App\View\Composers\GlobalDataComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('frontend.*', GlobalDataComposer::class);
        View::composer('layouts.frontend', GlobalDataComposer::class);
    }
}
