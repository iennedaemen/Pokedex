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
        Schema::create('version_pokemon_id', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id');
            $table->foreignId('pokemon_id');
            $table->integer('index')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('version_pokemon_id');
    }
};