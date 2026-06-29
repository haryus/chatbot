<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('f_a_q_s', function (Blueprint $table) {
            $table->string('question')->after('id');
            $table->text('answer')->after('question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('f_a_q_s', function (Blueprint $table) {
            $table->dropColumn('question');
            $table->dropColumn('answer');
        });
    }
};
