<?php

namespace App\Http\Controllers;

use App\Models\Headliner;
use App\Models\Speaker;
use App\Http\Requests\StoreSpeakerRequest;
use App\Http\Requests\UpdateSpeakerRequest;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\isNull;

class HeadlinerController extends Controller
{
    function create() {
        $req = $this->validate([
            'speaker_id' => 'required|exists:speakers,id',
            'stage_id' => 'required|exists:stages,id'
        ]);

        $headliner = Headliner::factory()->make([
            'speaker_id' => $req['speaker_id'],
            'stage_id' => $req['stage_id']
        ]);

        $headliner->save();

        return response()->json(['headliner' => $headliner->with(['speaker', 'stage'])]);
    }

    function index() {
        return response()->json(['headliners' => Headliner::with(['speaker', 'stage'])]);
    }

    function edit() {
        $req = $this->validate([
            'id' => 'required|exists:headliners,id',
            'speaker_id' => 'required|exists:speakers,id',
            'stage_id' => 'required|exists:stages,id'
        ]);

        $headliner = Headliner::find($req["id"]);

        $headliner->speaker_id = $req["speaker_id"];
        $headliner->stage_id = $req["stage_id"];

        $headliner->save();

        return response()->json(['headliner' => $headliner->with(['speaker', 'stage'])]);
    }

    function delete() {
        $req = $this->validate([
            'id' => 'required|exists:headliners,id'
        ]);

        $headliner = Headliner::find($req["id"]);

        $headliner->delete();

        return response()->json();
    }
}
