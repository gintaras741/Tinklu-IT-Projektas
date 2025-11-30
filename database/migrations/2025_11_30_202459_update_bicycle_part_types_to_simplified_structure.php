<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing part types to new simplified structure
        // Old types: fork, seat_post, headset, seat_clamp, tires
        // New types: frame, wheels, handlebars, saddle, pedals, chain
        
        DB::table('bicycle_parts')
            ->where('type', 'tires')
            ->update(['type' => 'wheels']);
            
        DB::table('bicycle_parts')
            ->whereIn('type', ['fork', 'headset'])
            ->update(['type' => 'handlebars']);
            
        DB::table('bicycle_parts')
            ->whereIn('type', ['seat_post', 'seat_clamp'])
            ->update(['type' => 'saddle']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes (approximate)
        DB::table('bicycle_parts')
            ->where('type', 'wheels')
            ->update(['type' => 'tires']);
            
        DB::table('bicycle_parts')
            ->where('type', 'handlebars')
            ->update(['type' => 'fork']);
            
        DB::table('bicycle_parts')
            ->where('type', 'saddle')
            ->update(['type' => 'seat_post']);
    }
};
