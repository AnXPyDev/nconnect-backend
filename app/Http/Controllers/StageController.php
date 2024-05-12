<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    function create() {
        $req = request()->all();

        $stage = Stage::factory()->make([
            "name" => $req["name"],
        ])->save();

        return response()->json(['stage' => $stage]);
    }

    function index() {
        return response()->json(['stages' => Stage::all()]);
    }

    function presentations() {
        $req = request()->all();

        $stage = Stage::find($req["id"]);

        return response()->json([ 'presentations' => $stage->presentations() ]);
    }
}
