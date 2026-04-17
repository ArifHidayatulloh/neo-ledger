<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('spks', function (Blueprint $table) {
            $table->text('context')->nullable()->after('final_bill');
        });
    }

    public function down()
    {
        Schema::table('spks', function (Blueprint $table) {
            $table->dropColumn('context');
        });
    }
};
