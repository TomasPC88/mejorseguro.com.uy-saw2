<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdministradoresController extends BaseSectionController
{

    /**
     * PortadaController constructor.
     */
    public function __construct()
    {
        $this->name = 'administradores';
        $this->title = 'Administradores';
        $this->model = Admin::class;

        parent::__construct();
        view()->share([
            'name' => $this->name,
            'title' => $this->title,
        ]);
    }

    //Redefine Methods here
    
    public function update($id, Request $request)
    {
        $this->performUpdate($id, $request);

        $this->instance->roles()->sync(array_clear(explode(',',$request->get('roles'))));

        return $this->send($this->instance);
    }

    public function store(Request $request)
    {
        $this->performStore($request);

        $this->instance->roles()->sync(array_clear(explode(',',$request->get('roles'))));

        return $this->send($this->instance);
    }
}
