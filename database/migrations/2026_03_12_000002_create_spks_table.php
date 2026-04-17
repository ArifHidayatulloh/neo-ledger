<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('spks', function (Blueprint $table) {
            $table->id();
            $table->string('spk_ref')->unique();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->decimal('total_contract', 15, 2)->default(0);
            $table->decimal('dp_amount', 15, 2)->default(0);
            $table->decimal('final_bill', 15, 2)->default(0);
            $table->enum('status', ['draft','sent','paid','overdue'])->default('draft');
            $table->boolean('is_finalized')->default(false);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('spks');
    }
};
