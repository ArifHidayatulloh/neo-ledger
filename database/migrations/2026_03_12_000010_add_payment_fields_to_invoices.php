<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('bank_account');
            $table->string('bank_number')->nullable()->after('bank_name');
            $table->date('due_date')->nullable()->after('bank_number');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'bank_number', 'due_date']);
        });
    }
};
