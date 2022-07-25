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
        Schema::create('move_pokemon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('move_id');
            $table->foreignId('pokemon_id');
            $table->integer('learn_level')->unsigned();
            $table->string('learn_method');
            $table->foreignId('version_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('move_pokemon');
    }
};