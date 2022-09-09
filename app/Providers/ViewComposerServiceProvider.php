<?php

namespace App\Providers;

use App\Http\Requests\HaciendaRequest;
use App\Models\Auto;
use App\Models\Faq;
use App\Models\Propiedad;
use App\Repositories\AgenteRepository;
use App\Repositories\GeoRepository;
use App\Repositories\HaciendaRepository;
use App\Repositories\PropiedadRepository;
use App\Repositories\RemateRepository;
use App\Repositories\RuralRepository;
use App\Repositories\UrbanaRepository;
use App\Repositories\VentaRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    protected $repositories;

    /**
     * ViewComposerServiceProvider constructor.
     */
    public function __construct()
    {

    }


    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {

        //Datos compartidos a vistas aqui

        View::composer(['page.*'], function ($view) {
           /* $data = [];
            return $view->with(compact('data'));*/
        });

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
