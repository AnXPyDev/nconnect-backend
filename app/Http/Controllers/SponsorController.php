<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Http\Requests\StoreSpeakerRequest;
use App\Http\Requests\UpdateSpeakerRequest;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\isNull;

class SponsorController extends Controller
{
    function create() {
        $req = $this->validate([
            'name' => 'required',
            'description' => 'nullable',
            'image_id' => 'nullable|exists:resources,id',
            'contact' => 'nullable|array',
        ]);

        $sponsor = Sponsor::factory()->make([
            "name" => $req["name"],
            "description" => $req["description"],
            "image_id" => $req["image_id"] ?? null,
            "contact" => $req["contact"] ?? null,
        ]);

        $sponsor->save();

        return response()->json(['sponsor' => $sponsor]);
    }

    function index() {
        return response()->json(['sponsors' => Sponsor::all()]);
    }

    function edit() {
        $req = $this->validate([
            'id' => 'required|exists:sponsors,id',
            'name' => 'required',
            'description' => 'nullable',
            'image_id' => 'nullable|exists:resources,id',
            'contact' => 'nullable|array'
        ]);

        $sponsor = Sponsor::find($req["id"]);

        $sponsor->name = $req["name"];
        $sponsor->description = $req["description"];
        $sponsor->image_id = $req["image_id"] ?? null;
        $sponsor->contact = $req["contact"] ?? null;

        $sponsor->save();

        return response()->json(['sponsor' => $sponsor]);
    }

    function delete() {
        $req = $this->validate([
            'id' => 'required|exists:sponsors,id'
        ]);

        $sponsor = Sponsor::find($req["id"]);

        $sponsor->delete();

        return response()->json();
    }
}
