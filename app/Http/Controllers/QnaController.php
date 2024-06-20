<?php

namespace App\Http\Controllers;

use App\Models\Qna;

class QnaController extends Controller
{

    function create() {
        $req = $this->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $qna = Qna::factory()->create([
            "question" => $req["question"],
            "answer" => $req["answer"]
        ]);

        return response()->json(['qna' => $qna]);
    }

    function index() {
        return response()->json(['qnas' => Qna::all()]);
    }

    function edit() {
        $req = $this->validate([
            'id' => 'required|exists:qna,id',
            'question' => 'required',
            'answer' => 'required',
        ]);

        $qna = Qna::find($req["id"]);

        $qna->question = $req["question"];
        $qna->answer = $req["answer"];

        $qna->save();

        return response()->json(['qna' => $qna]);
    }

    function delete() {
        $req = $this->validate([
            'id' => 'required|exists:qnas,id'
        ]);

        $testimonial = Qna::find($req["id"]);

        $testimonial->delete();

        return response()->json();
    }
}
