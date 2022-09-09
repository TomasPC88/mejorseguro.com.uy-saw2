<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuscriptionRequest;
use App\Mail\CanceledSubscriptionMail;
use App\Mail\NewSuscriptionMail;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class SuscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('grecaptcha')->only([
            'suscribe'
        ]);
    }

    public function suscribe(SuscriptionRequest $request)
    {
        $requestData = $request->all();
        $newsletter = Newsletter::where('email', $requestData['email'])->first();
            if ($newsletter) {
                if ($newsletter->active != false) {
                    return response()->json([
                        'message' => ['type' => 'danger', 'text' => 'Esta dirección de correo está en uso y activa']
                    ]);
                }

                Mail::to($newsletter->email)->send(new NewSuscriptionMail($requestData, $newsletter->email, $newsletter->token));
                $newsletter->update(['active' => 1]);
                return response()->json([
                    'message' => ['type' => 'success', 'text' => sprintf('Su suscripción en %s ha sido reactivada', env('APP_NAME'))]
                ]);
            }
            DB::beginTransaction();
            $newsletter = new Newsletter();
            $newsletter->email = $requestData['email'];
            $newsletter->active = 1;
            $newsletter->token = hash_hmac('sha256', Str::random(50), config('app.key'));
            $newsletter->save();

            Mail::to($newsletter->email)->send(new NewSuscriptionMail($requestData, $newsletter->email, $newsletter->token));
            DB::commit();
            return response()->json([
                'message' => ['type' => 'success', 'text' => sprintf('Gracias por suscribirse en %s', env('APP_NAME'))]
            ]);
    }

    public function unsuscribe(Request $request)
    {
        if ($request->has('token')) {
            $newsletter = Newsletter::where('token', $request->get('token'))->first();
            if ($newsletter) {
                $newsletter->token = hash_hmac('sha256', Str::random(40), config('app.key'));
                $newsletter->active = 0;
                $newsletter->save();
                Mail::to($newsletter->email)->send(new CanceledSubscriptionMail());
                return view('page.auth.suscription_canceled');
            }

            abort(404);
        }
        abort(404);
    }
}
