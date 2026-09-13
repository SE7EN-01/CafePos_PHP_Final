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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('total_amount');
            $table->string('khqr_md5')->nullable()->after('payment_method');
            $table->timestamp('khqr_expires_at')->nullable()->after('khqr_md5');
            $table->timestamp('paid_at')->nullable()->after('khqr_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'khqr_md5',
                'khqr_expires_at',
                'paid_at',
            ]);
        });
    }
};
