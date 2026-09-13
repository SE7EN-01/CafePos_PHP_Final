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
        // 1. Suppliers Table
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Enhance Ingredients Table
        Schema::table('ingredients', function (Blueprint $table) {
            $table->string('sku')->nullable()->unique()->after('name');
            $table->decimal('purchase_cost', 10, 2)->default(0.0)->after('reorder_level');
            $table->decimal('average_cost', 10, 2)->default(0.0)->after('purchase_cost');
            $table->foreignId('supplier_id')->nullable()->after('average_cost')->constrained('suppliers')->nullOnDelete();
            $table->date('expiry_date')->nullable()->after('supplier_id');
            $table->string('batch_number')->nullable()->after('expiry_date');
            $table->text('notes')->nullable()->after('batch_number');
        });

        // 3. Add inventory_deducted_at to orders for duplicate prevention
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('inventory_deducted_at')->nullable()->after('paid_at');
        });

        // 4. Purchases Table
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('invoice_number')->nullable();
            $table->date('purchase_date')->default(now());
            $table->string('status')->default('received'); // pending, received, cancelled
            $table->decimal('total_amount', 10, 2)->default(0.0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 5. Purchase Items Table
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained('ingredients')->cascadeOnDelete();
            $table->decimal('quantity', 10, 2);
            $table->string('unit');
            $table->decimal('unit_cost', 10, 2)->default(0.0);
            $table->decimal('subtotal', 10, 2)->default(0.0);
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('inventory_deducted_at');
        });

        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn([
                'sku',
                'purchase_cost',
                'average_cost',
                'supplier_id',
                'expiry_date',
                'batch_number',
                'notes',
            ]);
        });

        Schema::dropIfExists('suppliers');
    }
};
