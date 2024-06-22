<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Mail\RegisterMail;
use App\Mail\TestMail;
use App\Mail\UnregisterMail;
use App\Models\Timeslot;
use App\Models\User;
use App\Models\UserTimeslotPivot;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Codes;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    function register() {
        $req = $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users'
        ]);

        $user = User::factory()->create([
            'name' => $req['name'],
            'email' => $req['email']
        ]);

        $token = $user->token()->plainTextToken;

        Mail::to($user->email)->queue(new RegisterMail($user->name, $token));

        return response()->json([
            'data' => $user,
            'token' => $token
        ]);
    }

    function unregister() {
        $user = request()->user();

        UserTimeslotPivot::where('user_id', $user->id)->delete();
        $user->currentAccessToken()->delete();
        $user->delete();


        Mail::to($user->email)->queue(new UnregisterMail($user->name));

        return response()->json([]);
    }

    function info() {
        $user = request()->user();

        return response()->json([
            "data" => [
                ...$user->toArray(),
                'timeslots' => $user->timeslots()->pluck('timeslots.id')->toArray()
            ]
        ]);
    }

    function registertimeslot() {
        $req = $this->validate([
            'id' => 'required|exists:timeslots,id',
        ]);

        $user = request()->user();

        $user->load('timeslots');

        $timeslot = Timeslot::find($req['id']);

        if ($timeslot->remaining_capacity < 1) {
            return response()->json([
                'code' => Codes::OCCUPIED,
                'message' => 'This presentation is already full'
            ]);
        }

        foreach($user->timeslots as $other) {
            if ($timeslot->checkOverlap($other)) {
                $other->load('presentation');
                return response()->json([
                    'code' => Codes::OVERLAP,
                    'overlap' => $other,
                    'message' => 'Timeslot overlaps'
                ]);
            }
        }

        UserTimeslotPivot::factory()->create([
            'user_id' => $user->id,
            'timeslot_id' => $timeslot->id
        ]);

        return response()->json([]);
    }

    function internal_unregistertimeslots(User $user, Timeslot $timeslot) {
        $pivot = UserTimeslotPivot::where('user_id', $user->id)->where('timeslot_id', $timeslot->id);

        if (!$pivot->exists()) {
            response()->json([
                'code' => Codes::NOTFOUND,
                'message' => "Not registered for this timeslot"
            ])->throwResponse();
        }

        $pivot->first()->delete();
    }


    function unregistertimeslot() {
        $req = $this->validate([
            'id' => 'required|exists:timeslots,id',
        ]);

        $user = request()->user();

        $user->load('timeslots');

        $timeslot = Timeslot::find($req['id']);

        $this->internal_unregistertimeslots($user, $timeslot);

        return response()->json([]);
    }

    function adminunregistertimeslot() {
        $req = $this->validate([
            'id' => 'required|exists:users,id',
            'timeslots_id' => 'required|exists:timeslots,id'
        ]);

        $user = User::find($req['id']);

        $user->load('timeslots');

        $timeslot = Timeslot::find($req['id']);

        $this->internal_unregistertimeslots($user, $timeslot);

        return response()->json([]);
    }

    function index() {
        return response()->json([
            'users' => User::all()
        ]);
    }

    function mytimeslots() {
        $user = request()->user();

        return response()->json([
            'timeslots' => $user->timeslots()->with(['stage', 'presentation', 'presentation.speaker'])->get()
        ]);
    }

    function timeslots() {
        $req = $this->validate([
            'id' => 'required|exists:users,id',
        ]);

        $user = User::find($req['id']);

        return response()->json([
            'timeslots' => $user->timeslots()->with(['stage', 'presentation'])->get()
        ]);
    }
}
