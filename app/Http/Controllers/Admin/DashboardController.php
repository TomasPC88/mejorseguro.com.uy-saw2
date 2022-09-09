<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class DashboardController extends BaseSectionController
{

    //Methods here
    /**
     * PortadaController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->name = 'dashboard';
        $this->title = 'Dashboard';
        view()->share([
            'name' => $this->name,
            'title' => $this->title,
        ]);
    }

    public function index(Request $request, $col = 'pos')
    {
        return view("admin.{$this->name}.home");
    }

}
