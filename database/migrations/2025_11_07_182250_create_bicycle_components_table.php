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
        Schema::create('bicycle_components', function (Blueprint $table) {
            $table->primary(['bicycle_id', 'bicycle_part_id']);
            $table->foreignId('bicycle_id')->constrained()->onDelete('cascade');
            $table->foreignId('bicycle_part_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bicycle_components');
    }
};
