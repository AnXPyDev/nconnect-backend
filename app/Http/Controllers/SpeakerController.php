<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use App\Http\Requests\StoreSpeakerRequest;
use App\Http\Requests\UpdateSpeakerRequest;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\isNull;

class SpeakerController extends Controller
{
    function create() {
        $req = $this->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);

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

    function edit() {
        $req = $this->validate([
            'id' => 'required|exists:speakers,id',
            'name' => 'required',
            'description' => 'nullable'
        ]);

        $speaker = Speaker::find($req["id"]);

        $speaker->name = $req["name"];
        $speaker->description = $req["description"];

        $speaker->save();

        return response()->json(['speaker' => $speaker]);
    }

    function delete() {
        $req = $this->validate([
            'id' => 'required|exists:speakers,id'
        ]);

        $speaker = Speaker::find($req["id"]);

        $speaker->delete();

        return response()->json();
    }
}
