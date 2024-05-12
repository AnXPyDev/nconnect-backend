<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use App\Http\Requests\StoreSpeakerRequest;
use App\Http\Requests\UpdateSpeakerRequest;

class SpeakerController extends Controller
{
    function create() {
        $req = request()->all();

        $speaker = Speaker::factory()->make([
            "name" => $req["name"],
            "description" => $req["description"]
        ]);

        $speaker->save();

        return response()->json(['speaker' => $speaker]);
    }

    function index() {
        return response()->json(['speakers' => Speaker::all()]);
    }
}
