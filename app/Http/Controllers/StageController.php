<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    function create() {
        $req = request()->all();

        $stage =  Stage::factory()->make([
            "name" => $req["name"],
        ]);

        $stage->save();

        return response()->json(['stage' => $stage]);
    }

    function index() {
        return Stage::all();
    }

    function presentations() {
        $req = request()->all();

        return response()->json(['presentations' => Stage::find($req["id"])->presentations()]);
    }
}
