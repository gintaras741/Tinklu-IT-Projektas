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
        Schema::table('part_items', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->after('cart_id')->constrained()->onDelete('cascade');
            $table->decimal('price_at_purchase', 10, 2)->nullable()->after('amount');
            $table->decimal('subtotal', 10, 2)->nullable()->after('price_at_purchase');
        });

        Schema::table('bicycle_items', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->after('cart_id')->constrained()->onDelete('cascade');
            $table->decimal('price_at_purchase', 10, 2)->nullable()->after('amount');
            $table->decimal('subtotal', 10, 2)->nullable()->after('price_at_purchase');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('part_items', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn(['order_id', 'price_at_purchase', 'subtotal']);
        });

        Schema::table('bicycle_items', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn(['order_id', 'price_at_purchase', 'subtotal']);
        });
    }
};
