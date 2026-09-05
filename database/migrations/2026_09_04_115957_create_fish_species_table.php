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
        Schema::create('fish_species', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 150)->unique();
            $table->string('local_name', 150)->nullable();
            $table->string('english_name', 150)->nullable();
            $table->string('tausug_name', 150)->nullable();
            $table->string('scientific_name', 150)->nullable();
            $table->unsignedTinyInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('list_dropdowns');
            $table->string('unit', 20)->default('kg');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fish_species');
    }
};
