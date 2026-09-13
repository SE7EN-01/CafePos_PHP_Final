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
        Schema::table('ingredients', function (Blueprint $table) {
            $table->decimal('purchase_cost', 10, 4)->default(0.0000)->change();
            $table->decimal('average_cost', 10, 4)->default(0.0000)->change();
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->decimal('unit_cost', 10, 4)->default(0.0000)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->decimal('purchase_cost', 10, 2)->default(0.0)->change();
            $table->decimal('average_cost', 10, 2)->default(0.0)->change();
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->decimal('unit_cost', 10, 2)->default(0.0)->change();
        });
    }
};
