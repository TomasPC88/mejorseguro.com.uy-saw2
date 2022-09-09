<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;

class RestController extends Controller
{

    //JSON Responses
    private function send($data, $message = null)
    {
        return response()->json([
            'status' => 'OK',
            'data' => $data,
            'message' => $message
        ]);
    }

    private function errorResponse($message, $code = 403)
    {
        return response()->json([
            'status' => 'ERROR',
            'message' => $message,
        ], $code);
    }
}
