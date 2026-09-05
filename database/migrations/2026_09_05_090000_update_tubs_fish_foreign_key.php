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
        Schema::table('tubs', function (Blueprint $table) {
            $table->dropForeign(['fish_id']);
            $table->foreign('fish_id')->references('id')->on('fish_species');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tubs', function (Blueprint $table) {
            $table->dropForeign(['fish_id']);
            $table->foreign('fish_id')->references('id')->on('list_names');
        });
    }
};
