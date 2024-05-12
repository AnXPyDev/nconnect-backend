<?php

use App\Models\Presentation;
use App\Models\Stage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('timeslots', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->dateTime("start_at");
            $table->dateTime("end_at");

            $table->foreignIdFor(Presentation::class)->nullable();
            $table->foreignIdFor(Stage::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timeslots');
    }
};
