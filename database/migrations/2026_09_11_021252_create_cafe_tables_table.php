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
        Schema::create('cafe_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('capacity')->default(2);
            $table->string('location')->nullable();
            $table->enum('status', ['available', 'occupied', 'reserved'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('cafe_table_id')->nullable()->after('user_id')->constrained('cafe_tables')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['cafe_table_id']);
            $table->dropColumn('cafe_table_id');
        });

        Schema::dropIfExists('cafe_tables');
    }
};
