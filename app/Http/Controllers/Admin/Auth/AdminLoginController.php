<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Auth;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.auth.admin-login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if( $request->ajax() ){
            if( Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember) ){
                return response()->json([
                    'error' => '',
                    'route' => route('admin.dashboard.index')
                ]);
                // return redirect()->intended(route('admin.dashboard'));
            }
            
            return response()->json([
                'error' => $request->only('email', 'remember'),
                'route' => route('admin.dashboard.index')
            ]);

            // return redirect()->back()->withInput($request->only('email', 'remember'));
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('admin/');
    }
}
