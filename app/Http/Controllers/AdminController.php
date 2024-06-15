<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use App\Http\Codes;

class AdminController extends Controller
{
    public function login(Request $request) {

        $req = $this->validate([
            'username' => 'required|exists:admins,username',
            'password' => 'required|string'
        ]);

        $admin = Admin::where('username', $req['username'])->first();

        if (!Hash::check($req['password'], $admin->password_hash)) {
            return response()->json([
                'code' => Codes::WRONGPASS,
                'message' => "Wrong password"
            ]);
        }

        return response()->json([
            'token' => $admin->createToken("auth_token", ["admin"])->plainTextToken,
            'data' => $admin
        ]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([]);
    }

    public function info(Request $request) {
        return response()->json([
            "data" => []
        ]);
    }
}
