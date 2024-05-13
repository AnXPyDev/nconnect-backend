<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;

class AdminController
{
    public function login(Request $request) {
        $admin = Admin::all()->first();

        return response()->json([
            'token' => $admin->createToken("auth_token", ["admin"])->plainTextToken
        ]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([]);
    }

    public function test(Request $request) {
        return response()->json([
            'message' => 'hello admin'
        ]);
    }
}
