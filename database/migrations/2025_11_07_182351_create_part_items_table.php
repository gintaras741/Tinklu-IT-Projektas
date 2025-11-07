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
        Schema::create('part_items', function (Blueprint $table) {
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('bicycle_part_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('amount');
            $table->timestamps();
            $table->primary(['cart_id', 'bicycle_part_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_items');
    }
};
