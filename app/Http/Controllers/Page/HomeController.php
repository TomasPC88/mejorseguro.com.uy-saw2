<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Portada;

class HomeController extends Controller
{
    public function index(){
        $home['portadas'] = Portada::with('medias')->whereActive(true)->get();
        return view('page.home',$home);
    }

    public function changeLocale(Request $request)
    {
        Session::put('locale', $request->get('lang'));
        Session::flash('locale-changed', app()->getLocale());
        return response()->json([
            'status' => 'OK'
        ]);
    }
}
