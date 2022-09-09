<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends BaseSectionController
{

    /**
     * PortadaController constructor.
     */
    public function __construct()
    {
        $this->name = 'roles';
        $this->title = 'Roles';
        $this->model = Role::class;

        parent::__construct();
        view()->share([
            'name' => $this->name,
            'title' => $this->title,
        ]);
    }

    public function permissions($guard){
        return response()->json([
            'data'=> Permission::where('guard_name',$guard)->get()
        ]);
    }
    
    //Redefine Methods here

    public function store(Request $request)
    {
        $this->performStore($request);

        $this->instance->permissions()->sync(array_clear(explode(',',$request->get('permissions'))));

        return $this->send($this->instance);
    }
    
    public function update($id, Request $request)
    {
        $this->performUpdate($id, $request);

        $this->instance->permissions()->sync(array_clear(explode(',',$request->get('permissions'))));

        return $this->send($this->instance);
    }
}
