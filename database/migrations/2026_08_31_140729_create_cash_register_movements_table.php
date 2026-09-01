<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cash_register_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_register_session_id')->constrained('cash_register_sessions')->onDelete('cascade');
            $table->enum('type', ['in', 'out'])->comment('in = ingreso, out = retiro');
            $table->decimal('amount', 10, 2);
            $table->string('description');
            $table->foreignId('authorized_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_register_movements');
    }
};