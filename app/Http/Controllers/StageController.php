<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    function create() {
        $req = $this->validate([
            'name' => 'required',
        ]);

        $stage =  Stage::factory()->make([
            "name" => $req["name"],
        ]);

        $stage->save();

        return response()->json(['stage' => $stage]);
    }

    function index() {
        return response()->json(['stages' => Stage::all()]);
    }

    function edit() {
        $req = $this->validate([
            "id" => "required|exists:stages,id",
            "name" => "required"
        ]);

        $stage = Stage::find($req["id"]);

        $stage->name = $req["name"];

        $stage->save();

        return response()->json(['stage' => $stage]);
    }

    function delete() {
        $req = $this->validate([
            'id' => 'required|exists:stages,id'
        ]);

        $stage = Stage::find($req["id"]);

        $stage->delete();

        return response()->json();
    }

    function timeslots() {
        $req = $this->validate([
            'id' => 'required|exists:stages,id'
        ]);


        $stage = Stage::find($req["id"]);

        return response()->json(['timeslots' => $stage->timeslots()->get()]);

    }
}
