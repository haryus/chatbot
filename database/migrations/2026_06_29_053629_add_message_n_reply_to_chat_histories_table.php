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
        Schema::table('chat_histories', function (Blueprint $table) {
            $table->text('message')->after('id');
            $table->text('reply')->after('message');
            $table->unsignedBigInteger('user_id')->after('reply');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_histories', function (Blueprint $table) {
            $table->dropColumn('message');
            $table->dropColumn('reply');
            $table->dropColumn('user_id');
        });
    }
};
