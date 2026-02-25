<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->decimal('limit_amount', 15, 2);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->string('period', 7); // Format: "2026-02" (YYYY-MM)
            $table->boolean('alert_sent_80')->default(false);
            $table->boolean('alert_sent_100')->default(false);
            $table->timestamps();

            $table->unique(['category_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
