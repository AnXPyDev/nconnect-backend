<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use App\Http\Codes;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;

class AdminController extends Controller
{
    public function login(Request $request) {

        $req = $this->validate([
            'username' => 'required|exists:admins,username',
            'password' => 'required|string'
        ]);

        $admin = Admin::where('username', $req['username'])->first();

        if (!$admin->checkPassword($req['password'])) {
            return response()->json([
                'code' => Codes::WRONGPASS,
                'message' => "Wrong password"
            ]);
        }

        return response()->json([
            'token' => $admin->tokenWithPriv()->plainTextToken,
            'data' => $admin
        ]);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json([]);
    }

    public function info(Request $request) {
        return response()->json([
            "data" => $request->user()
        ]);
    }

    public function setpriv() {
        $req = $this->validate([
            'id' => 'required|exists:admins,id',
            'priv' => 'required|int'
        ]);

        $current = request()->user();

        if ($current->id == $req['id']) {
            return response()->json([
                'code' => Codes::BADINPUT,
                'message' => "You can't change your own priviliges"
            ]);
        }

        Admin::checkPriv($req['priv']);

        $admin = Admin::find($req['id']);

        $admin->priv = $req['priv'];

        $admin->save();

        $admin->tokens()->delete();

        return response()->json([
            'admin' => $admin
        ]);
    }

    public function delete() {
        $req = $this->validate([
            'id' => 'required|exists:admins,id'
        ]);

        $current = request()->user();

        if ($current->id == $req['id']) {
            return response()->json([
                'code' => Codes::BADINPUT,
                'message' => "You can't delete yourself"
            ]);
        }

        $admin = Admin::find($req['id']);

        $admin->tokens()->delete();
        $admin->delete();

        return response()->json([]);
    }


    public function changepassword() {
        $req = $this->validate([
            'password' => 'required|string'
        ]);

        $admin = request()->user();

        Admin::passwordStrength($req['password']);

        $admin->changePassword($req['password']);

        return response()->json([]);
    }

    public function register() {
        $req = $this->validate([
            'username' => 'required|unique:admins,username',
            'password' => 'required|string',
            'priv' => 'required|int'
        ]);

        Admin::passwordStrength($req['password']);
        Admin::checkPriv($req['priv']);

        $admin = Admin::register($req['username'], $req['password'], $req['priv']);

        return response()->json([
            'admin' => $admin
        ]);
    }

    public function index() {
        return response()->json([
            'admins' => Admin::all()
        ]);
    }
}
