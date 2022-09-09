<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;

class WoValidationException extends Exception
{
    protected $validator;

    protected $code;

    public function __construct(Validator $validator,$code = 200)
    {
        $this->validator = $validator;
        $this->code = $code;
    }

    public function render()
    {
        // return a json with desired format
        return response()->json([
            "status" => 'ERROR',
            "message" => __("validation.invalid_form"),
            "errors" => $this->validator->errors()
        ], $this->code);
    }
}