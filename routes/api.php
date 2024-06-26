<?php

use App\Http\Codes;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\QnaController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\TimeslotController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\HeadlinerController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\ResourceController;

use App\Http\Middleware\Cors;

Route::post("ping", function() {
    return response()->json([
        "code" => Codes::OK
    ]);
});

Route::prefix("auth")->group(function () {
    Route::controller(AdminController::class)->prefix("admin")->group(function () {
        Route::post("/login", "login");
        Route::middleware('authx:admin')->group(function () {
            Route::post("/logout", "logout");
            Route::post("/changepassword", "changepassword");
            Route::post("/info", "info");
        });
        Route::middleware("authx:admin,priv-super")->group(function () {
            Route::post("/setpriv", "setpriv");
            Route::post("/register", "register");
            Route::post("/index", "index");
            Route::post("/delete", "delete");
        });
    });
});

Route::controller(UserController::class)->prefix("user")->group(function () {
    Route::post("/register", "register");
    Route::middleware("authx:user")->group(function () {
        Route::post("/unregister", "unregister");
        Route::post("/info", "info");
        Route::post("/registertimeslot", "registertimeslot");
        Route::post("/unregistertimeslot", "unregistertimeslot");
        Route::post("/mytimeslots", "mytimeslots");
    });
    Route::middleware("authx:admin,priv-view")->group(function () {
        Route::post("/index", "index");
        Route::post("/timeslots", "timeslots");
    });
    Route::middleware("authx:admin,priv-super")->group(function () {
        Route::post("/adminunregister", "adminunregister");
        Route::post("/adminunregistertimeslot", "adminunregistertimeslot");
    });
});

Route::controller(ConferenceController::class)->prefix("conference")->group(function () {
    Route::middleware('authx:admin,priv-edit')->group(function () {
        Route::post("/edit", "edit");
    });
    Route::post("/get", "get");
});

Route::controller(QnaController::class)->prefix("qna")->group(function () {
    Route::middleware('authx:admin,priv-edit')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
    });
    Route::post("/index", "index");
});

Route::controller(SpeakerController::class)->prefix("speaker")->group(function () {
    Route::middleware('authx:admin,priv-edit')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
    });
    Route::post("/index", "index");
    Route::post("/presentations", "presentations");
});

Route::controller(SponsorController::class)->prefix("sponsor")->group(function () {
    Route::middleware('authx:admin,priv-edit')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
    });
    Route::post("/index", "index");
});

Route::controller(HeadlinerController::class)->prefix("headliner")->group(function () {
    Route::middleware('authx:admin,priv-edit')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
    });
    Route::post("/index", "index");
});

Route::controller(StageController::class)->prefix("stage")->group(function () {
    Route::middleware('authx:admin,priv-edit')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
    });
    Route::post("/index", "index");
    Route::post("/timeslots", "timeslots");
    Route::post("/scheduleinfo", "scheduleinfo");
});

Route::controller(PresentationController::class)->prefix("presentation")->group(function () {
    Route::middleware('authx:admin,priv-edit')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
    });
    Route::post("/available", "available");
    Route::post("/events", "events");
});

Route::controller(TimeslotController::class)->prefix("timeslot")->group(function () {
    Route::middleware('authx:admin,priv-edit')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
    });
    Route::middleware('authx:admin,priv-view')->group(function () {
        Route::post("/users", "users");
    });
    Route::post("/presentation", "presentation");
});

Route::controller(TestimonialController::class)->prefix("testimonial")->group(function () {
    Route::middleware('authx:admin,priv-edit')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
    });
    Route::post("/index", "index");
});

Route::controller(ResourceController::class)->prefix("resource")->group(function () {
    Route::middleware('authx:admin,priv-edit')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::put("/upload", "upload");
        Route::post("/delete", "delete");
    });

    Route::post("/images", "images");
    Route::post("/pages", "pages");
    Route::post("/pagefromslug", "pagefromslug");
    Route::get("/get", "get");
});

Route::controller(GalleryController::class)->prefix("gallery")->group(function () {
    Route::middleware('authx:admin,priv-edit')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
        Route::post("/addimage", "addimage");
        Route::post("/createimage", "createimage");
        Route::post("/deleteimage", "deleteimage");
    });

    Route::post("/images", "images");
    Route::post("/index", "index");
    Route::post("/publicindex", "publicindex");
});

Route::controller(OrganizerController::class)->prefix("organizer")->group(function () {
    Route::middleware('authx:admin,priv-edit')->group(function () {
        Route::post("/create", "create");
        Route::post("/edit", "edit");
        Route::post("/delete", "delete");
    });
    Route::post("/index", "index");
});
