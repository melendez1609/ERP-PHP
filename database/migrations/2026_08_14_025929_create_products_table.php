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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('cost', 10, 2);
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->integer('min_stock');
            
            // Llave foránea hacia la tabla product_statuses
            $table->foreignId('product_status_id')->constrained('product_statuses');

            // Llave foránea hacia la tabla suppliers (opcional/nullable)
            $table->foreignId('supplier_id')
                  ->nullable()
                  ->constrained('suppliers')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};