<?php

namespace App\Providers;

use App\Models\Configuracion;
use Barryvdh\Debugbar\Facade as Debugbar;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Mapping para relaciones polimorficas

        /* Relation::morphMap([
             'translation'=>'App\Models\Translation'
         ]);*/

        //Cargo las configuraciones y las guardo en cache
        Cache::rememberForever('config', function () {
            if (Schema::hasTable('configuraciones'))
                return Configuracion::firstOrCreate(['id' => 1],[
                    'app_name' => 'SAW2'
                ]);

            return collect((new Configuracion())->getFillable())->recursive();
        });

        Schema::defaultStringLength(191);
        Paginator::defaultView('pagination::default');

        if ($this->app->environment('production'))
            DebugBar::disable();
    }
}
