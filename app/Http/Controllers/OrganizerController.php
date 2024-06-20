<?php

namespace App\Http\Controllers;

use App\Models\Organizer;

class OrganizerController extends Controller
{
    function create()
    {
        $req = $this->validate([
            'name' => 'required',
            'role' => 'required',
            'contact' => 'required|array',
            'image_id' => 'nullable|exists:resources,id'
        ]);

        $organizer = Organizer::factory()->create([
            "name" => $req["name"],
            "role" => $req["role"],
            "contact" => $req["contact"],
            "image_id" => $req["image_id"] ?? null
        ]);

        return response()->json(['organizer' => $organizer]);
    }

    function index()
    {
        return response()->json(['organizers' => Organizer::all()]);
    }

    function edit()
    {
        $req = $this->validate([
            'id' => 'required|exists:organizers,id',
            'name' => 'required',
            'role' => 'required',
            'contact' => 'required|array',
            'image_id' => 'nullable|exists:resources,id'
        ]);

        $organizer = Organizer::find($req["id"]);

        $organizer->name = $req["name"];
        $organizer->role = $req["role"];
        $organizer->contact = $req["contact"];
        $organizer->image_id = $req["image_id"] ?? null;

        $organizer->save();

        return response()->json(['organizer' => $organizer]);
    }

    function delete()
    {
        $req = $this->validate([
            'id' => 'required|exists:organizers,id'
        ]);

        $organizer = Organizer::find($req["id"]);

        $organizer->delete();

        return response()->json();
    }
}
