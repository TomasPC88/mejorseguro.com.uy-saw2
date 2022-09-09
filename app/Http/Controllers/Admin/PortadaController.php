<?php

namespace App\Http\Controllers\Admin;

use App\Models\Portada;

class PortadaController extends BaseSectionController
{

    /**
     * PortadaController constructor.
     */
    public function __construct()
    {
        $this->name = 'portadas';
        $this->title = 'Portadas';
        $this->model = Portada::class;

        parent::__construct();
        view()->share([
            'name' => $this->name,
            'title' => $this->title,
        ]);
    }

    //Redefine Methods here
}
