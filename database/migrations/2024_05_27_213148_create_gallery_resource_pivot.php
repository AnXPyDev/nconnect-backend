<?php

use App\Models\Gallery;
use App\Models\Resource;
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
        Schema::create('gallery_resource_pivot', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Gallery::class);
            $table->foreignIdFor(Resource::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_resource_pivot');
    }
};
