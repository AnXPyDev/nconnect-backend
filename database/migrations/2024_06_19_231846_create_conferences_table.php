<?php

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
        Schema::create('conferences', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->date('date');
            $table->unsignedTinyInteger('state');

            $table->string('subtitle');

            $table->string('about_title');
            $table->text('about_text');

            $table->string('presentation_title');
            $table->string('presentation_subtitle');

            $table->string('location_name');
            $table->string('location_city');
            $table->string('location_full');
            $table->string('location_link');
            $table->text('location_map_embed');

            $table->json('contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conferences');
    }
};
