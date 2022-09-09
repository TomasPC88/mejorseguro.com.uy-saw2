<?php

namespace App\Http\Controllers\Page\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Login constructor.
     */
    public function __construct()
    {
        $this->middleware('guest')->only('login');
        $this->middleware('auth')->only(['logout', 'profile','saveProfile']);
    }

    public function login()
    {
        return view('page.auth.login.home');
    }

    public function profile()
    {
        return view('page.auth.login.profile');
    }

    public function signin(SignInRequest $request)
    {
        $data = $request->all();
        $email = $data['email'];
        $password = $data['password'];
        $previous = $request->previous;
        $x = Session::all();
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            if (Auth::user()->active) {
                if (Session::has('completed')) {
                    $previous = '/perfil';
                    Session::forget('completed');
                }

                return response()->json([
                    'redirect' => $previous
                ]);
            }
            Auth::logout();
            return response()->json([
                'message' => ['type' => 'info', 'text' => 'Su usuario aún no ha sido activado, puede consultarnos en nuestra sección de contactos']
            ]);
        }
        return response()->json([
            'message' => ['type' => 'danger', 'text' => 'Usuario y/o contraseña inválidos']
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return back();
    }

    public function saveProfile(ProfileRequest $request)
    {
        $data = array_clear($request->all());

        DB::beginTransaction();
        $data['envio'] = isset($data['envio']) ? 1 : 0;

        Auth::user()->update($data);
        DB::commit();

        Session::put('profile-saved', 'true');
        return response()->json([
            'status' => 'OK',
            'message' => [
                'type' => 'sucess',
                'text' => 'Sus datos han sido actualizados'
            ],
            'redirect' => $request->previous
        ]);
    }
}
