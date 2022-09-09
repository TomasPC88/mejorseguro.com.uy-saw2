<?php

namespace App\Http\Controllers\Page\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\RecoverPasswordRequest;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Login constructor.
     */
    public function __construct()
    {
        $this->middleware('guest')->only('signup');
        $this->middleware('grecaptcha')->only([
            'recoverPassword',
            'saveNewPassword'
        ]);
    }

    public function forgotPassword()
    {
        return view('page.auth.passwords.forgot_password');
    }

    public function recoverPassword(ForgotPasswordRequest $request)
    {
        $requestData = $request->all();

        $user = User::whereEmail($requestData['email'])->first();

        Mail::to($requestData['email'])
            ->send(new ForgotPasswordMail($user));

        Session::push('recover-password', true);

        return response()->json([
            'message' => 'Se le ha enviado un correo eléctrónico con los pasos a seguir para recuperar su contraseña'
        ]);
    }


    public function askForNewPassword(Request $request)
    {
        if (!$request->has('token'))
            abort(404);

        $user = User::where('token', $request->token)->first();

        if (!$user) {
            return view('page.auth.passwords.expired_password_recover');
        }

        return view('page.auth.passwords.reestablish_password', [
            'id' => $user->id
        ]);
    }

    public function saveNewPassword(RecoverPasswordRequest $request)
    {
        $requestData = $request->all();

        $user = User::where('id', $requestData['user_id'])->first();
        $token = hash_hmac('sha256', Str::random(40), config('app.key'));
        $user->token = $token;
        $user->password = $requestData['password'];
        $user->save();

        Session::put('recover-success', true);

        return response()->json([
            'redirect' => route('page.auth.forgot-password.new-password.complete')
        ]);
    }

    public function recoverSuccess()
    {
        if (!Session::has('recover-success'))
            abort(404);

        Session::remove('recover-success');
        return view('page.auth.passwords.password_recovered');
    }
}
