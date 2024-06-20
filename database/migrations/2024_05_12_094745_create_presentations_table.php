<?php

use App\Models\Timeslot;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Stage;
use App\Models\Speaker;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presentations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('long_description')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->foreignId('image_id')->nullable()->constrained('resources');
            $table->boolean('generic')->default(false);
            $table->foreignIdFor(Speaker::class)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presentations');
    }
};
