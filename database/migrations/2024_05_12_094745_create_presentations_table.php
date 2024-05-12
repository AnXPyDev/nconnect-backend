<?php

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
            $table->text('description');

            $table->dateTime('start_date');
            $table->dateTime('end_date');

            $table->foreignIdFor(Stage::class);
            $table->foreignIdFor(Speaker::class);

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
