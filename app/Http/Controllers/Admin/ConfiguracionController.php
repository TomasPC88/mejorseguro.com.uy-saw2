<?php

namespace App\Http\Controllers\Admin;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ConfiguracionController extends BaseSectionController
{

    //Methods here
    /**
     * PortadaController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->name = 'configuracion';
        $this->title = 'Configuración';
        $this->model = Configuracion::class;
        view()->share([
            'name' => $this->name,
            'title' => $this->title,
        ]);
    }

    public function index(Request $request, $col='pos')
    {
        $this->instance = $this->model::firstOrfail();

        return view("admin.{$this->name}.home", [
            'data' => $this->instance,
            'url' => route("admin.{$this->name}.update", $this->instance->id),
        ]);
    }

    public function update($id, Request $request)
    {
        $this->performUpdate(...func_get_args());

        Cache::forget('config');

        return $this->raiseSuccessResponse();
    }
}
