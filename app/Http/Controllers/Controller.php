<?php

namespace App\Http\Controllers;

use App\Http\Codes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Controller
{

    function validate(array $rules) {
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            response()->json([
                "code" => Codes::BADINPUT,
                "message" => $validator->messages()->first()
            ])->throwResponse();
        }

        return $validator->attributes();
    }
    //
}
