<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('grecaptcha')->only([
            'send'
        ]);
    }

    public function index()
    {
        return view('page.contacto');
    }

    public function send(ContactRequest $request)
    {
        Mail::to(Cache::get('config')->mail_to_contact)->send(new ContactMail($request->all()));

        return response()->json([
            'message' => ['type' => 'success', 'text' => 'Su consulta fue enviada correctamente, espere su respuesta prontamente']
        ]);
    }
}
