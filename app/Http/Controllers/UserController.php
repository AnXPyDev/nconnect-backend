<?php

namespace App\Http\Controllers;

use App\Mail\RegisterMail;
use App\Mail\TestMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Codes;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    function register() {
        $req = $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users'
        ]);

        $user = User::factory()->create([
            'name' => $req['name'],
            'email' => $req['email']
        ]);

        $token = $user->token()->plainTextToken;

        Mail::to($user->email)->send(new RegisterMail($user->name, $token));

        return response()->json([
            'data' => $user,
            'token' => $token
        ]);
    }

    function info() {
        return response()->json([
            "data" => request()->user()
        ]);
    }

}
