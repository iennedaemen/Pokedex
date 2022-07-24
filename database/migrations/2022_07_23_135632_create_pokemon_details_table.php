<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pokemon_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pokemon_id');
            $table->string('type');
            $table->float('height');
            $table->float('weight');
            $table->string('moves');
            $table->integer('order');
            $table->string('ability_ids');
            $table->string('form');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pokemon_details');
    }
};