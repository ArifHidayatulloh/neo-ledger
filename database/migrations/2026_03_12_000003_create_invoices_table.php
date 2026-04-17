<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->unsignedBigInteger('spk_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->decimal('total_contract', 15, 2)->default(0);
            $table->decimal('dp_amount', 15, 2)->default(0);
            $table->decimal('final_bill', 15, 2)->default(0);
            $table->enum('status', ['draft','sent','paid','overdue'])->default('draft');
            $table->integer('sequence')->default(0);
            $table->smallInteger('year')->nullable();
            $table->tinyInteger('month')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('spk_id')->references('id')->on('spks')->onDelete('set null');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
