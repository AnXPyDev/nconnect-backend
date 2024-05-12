<?php

use App\Http\Controllers\PresentationController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\TimeslotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpeakerController;

Route::controller(SpeakerController::class)->prefix("speaker")->group(function () {
    Route::post("/create", "create");
    Route::post("/index", "index");
});

Route::controller(StageController::class)->prefix("stage")->group(function () {
    Route::post("/create", "create");
    Route::post("/index", "index");
    Route::post("/timeslots", "timeslots");
});

Route::controller(PresentationController::class)->prefix("presentation")->group(function () {
    Route::post("/create", "create");
});

Route::controller(TimeslotController::class)->prefix("timeslot")->group(function () {
    Route::post("/create", "create");
});
