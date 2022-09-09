<?php

namespace App\Http\Controllers\Page\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use App\Mail\UserRegisteredMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Login constructor.
     */
    public function __construct()
    {
        $this->middleware('guest')->only('signup');
        $this->middleware('grecaptcha')->only([
            'recoverPassword',
            'signup'
        ]);
    }

    public function register()
    {
        return view('page.auth.register.home');
    }

    public function signup(SignUpRequest $request)
    {
        $data = $request->all();
        $token = hash_hmac('sha256', Str::random(40), config('app.key'));
        $data['token'] = $token;
        DB::beginTransaction();
        $user = User::create($data);
        //Correo a Usuario

        Mail::to($user->email)
            ->send(new UserRegisteredMail($user));

        DB::commit();
        Session::put('user-created', true);

        return response()->json([
            'message' => 'Su usuario nuevo ha sido creado. Le hemos enviado un correo para confirmar el registro.'
        ]);
    }

    public function confirm(Request $request)
    {
        if (!$request->has('token'))
            return redirect()->to('/');

        $user = User::where('token', $request->token)->first();

        if (!$user) {
            return view('page.auth.register.expiration_mail_recover');
        }

        $user->active = 1;
        $user->token = hash_hmac('sha256', Str::random(40), config('app.key'));;
        $user->save();


        $markdown = new Markdown(view(), config('mail.markdown'));
        
        return $markdown->render('page.components.mails.user_activated', ['email' => $user->email]);
    }

    public function complete()
    {
        if (!Session::has('user-created'))
            abort(404);

        Session::forget('user-created');
        Session::put('completed', true);

        return view('page.auth.register.register_complete');
    }

}
