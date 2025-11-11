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
        Schema::table('bicycle_parts', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->after('amount')->default(0);
            $table->text('description')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bicycle_parts', function (Blueprint $table) {
            $table->dropColumn(['price', 'description']);
        });
    }
};
