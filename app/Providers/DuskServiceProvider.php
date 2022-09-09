<?php

namespace App\Providers;

use Laravel\Dusk\Browser;
use Illuminate\Support\ServiceProvider;

class DuskServiceProvider extends ServiceProvider
{
    /**
     * Register the Dusk's browser macros.
     *
     * @return void
     */
    public function boot()
    {
        Browser::macro('hasXScroll', function ($element = null) {
            $this->script("return window.innerWidth > document.body.clientWidth;");
            return $this;
        });
    }
}