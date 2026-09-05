<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->boolean('is_paid')->default(0)->after('is_sold');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['buyer_id']);
        });

        DB::statement('ALTER TABLE `sales` MODIFY `buyer_id` INT UNSIGNED NULL');

        Schema::table('sales', function (Blueprint $table) {
            $table->foreign('buyer_id')->references('id')->on('buyers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['buyer_id']);
        });

        DB::statement('ALTER TABLE `sales` MODIFY `buyer_id` INT UNSIGNED NOT NULL');

        Schema::table('sales', function (Blueprint $table) {
            $table->foreign('buyer_id')->references('id')->on('buyers');
            $table->dropColumn('is_paid');
        });
    }
};
