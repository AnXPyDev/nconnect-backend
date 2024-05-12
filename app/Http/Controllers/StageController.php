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

    function timeslots() {

        $req = request()->validate([
            'id' => 'required|exists:requests,id'
        ]);

        $stage = Stage::find($req["id"]);

        return response()->json(['timeslots' => $stage->timeslots()->get()->all()]);
    }
}
