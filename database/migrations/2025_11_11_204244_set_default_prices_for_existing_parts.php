<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set default random prices for existing parts that have price = 0
        $parts = DB::table('bicycle_parts')->where('price', 0)->get();
        
        foreach ($parts as $part) {
            DB::table('bicycle_parts')
                ->where('id', $part->id)
                ->update(['price' => rand(1000, 10000) / 100]); // Random price between 10 and 100 euros
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally reset prices to 0
        DB::table('bicycle_parts')->update(['price' => 0]);
    }
};
