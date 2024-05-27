<?php

namespace App\Http\Controllers;

use App\Http\Codes;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResourceController extends Controller {

    function create() {
        $req = $this->validate([
            'name' => 'required|string',
            'type' => 'required|string',
        ]);

        $resource = Resource::factory()->create([
            'name' => $req['name'],
            'type' => $req['type'],
        ]);

        return response()->json([
            'resource' => $resource
        ]);
    }

    function edit() {
        $req = $this->validate([
            'id' => 'required|exists:resources,id',
            'name' => 'required|string',
            'type' => 'required|string',
        ]);

        $resource = Resource::find($req['id']);

        $resource->name = $req['name'];
        $resource->type = $req['type'];

        $resource->save();

        return response()->json([
            'resource' => $resource
        ]);
    }

    function delete() {
        $req = $this->validate([
            'id' => 'required|exists:resources,id'
        ]);

        $resource = Resource::find($req['id']);

        if (!is_null($resource->path)) {
            Storage::delete($resource->path);
        }

        $resource->delete();
    }

    function upload() {
        $req = $this->validate([
            'id' => 'required|exists:resources,id',
            'extension' => 'required|string'
        ]);

        $resource = Resource::find($req['id']);

        if (!is_null($resource->path)) {
            Storage::delete($resource->path);
        }

        $resource->path = Str::uuid()->toString() . '.' . $req['extension'];

        Storage::put($resource->path, request()->getContent());

        $resource->save();

        return response()->json();
    }

    function get() {
        $req = $this->validate([
            'id' => 'required|exists:resources,id'
        ]);

        $resource = Resource::find($req['id']);

        if (is_null($resource->path)) {
            return response("File not available", 404);
        }

        $extension = pathinfo($resource->path, PATHINFO_EXTENSION);

        return Storage::download($resource->path, $resource->name . $extension);
    }

    function images() {
        return response()->json([
            'images' => Resource::all()->where('type', 'image')
        ]);
    }
}
