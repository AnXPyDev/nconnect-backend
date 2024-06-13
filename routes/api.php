<?php

use App\Http\Codes;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\TimeslotController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\HeadlinerController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\ResourceController;


Route::post("ping", function() {
    return response()->json([
        "code" => Codes::OK
    ]);
});

Route::prefix("auth")->group(function () {
    Route::controller(AdminController::class)->prefix("admin")->group(function () {
        Route::post("/login", "login");
        Route::middleware('auth:admin')->group(function () {
            Route::post("/logout", "logout");
            Route::post("/info", "info");
        });
    });
});

Route::controller(SpeakerController::class)->prefix("speaker")->group(function () {
    Route::middleware('auth:admin')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
    });
    Route::post("/index", "index");
    Route::post("/presentations", "presentations");
});

Route::controller(SponsorController::class)->prefix("sponsor")->group(function () {
    Route::middleware('auth:admin')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
    });
    Route::post("/index", "index");
});

Route::controller(HeadlinerController::class)->prefix("headliner")->group(function () {
    Route::middleware('auth:admin')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
    });
    Route::post("/index", "index");
});

Route::controller(StageController::class)->prefix("stage")->group(function () {
    Route::middleware('auth:admin')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
    });
    Route::post("/index", "index");
    Route::post("/timeslots", "timeslots");
});

Route::controller(PresentationController::class)->prefix("presentation")->group(function () {
    Route::middleware('auth:admin')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
    });
    Route::post("/available", "available");
    Route::post("/events", "events");
});

Route::controller(TimeslotController::class)->prefix("timeslot")->group(function () {
    Route::middleware('auth:admin')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
        Route::post("/setpresentation", "setpresentation");
    });
    Route::post("/presentation", "presentation");
});

Route::controller(TestimonialController::class)->prefix("testimonial")->group(function () {
    Route::middleware('auth:admin')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
    });
    Route::post("/index", "index");
});

Route::controller(ResourceController::class)->prefix("resource")->group(function () {
    Route::middleware('auth:admin')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::put("/upload", "upload");
    });

    Route::post("/images", "images");
    Route::get("/get", "get");
});

Route::controller(GalleryController::class)->prefix("gallery")->group(function () {
    Route::middleware('auth:admin')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
        Route::post("/addimage", "addimage");
        Route::post("/createimage", "createimage");
        Route::post("/deleteimage", "deleteimage");
    });

    Route::post("/images", "images");
    Route::post("/index", "index");
});
