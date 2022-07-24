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
        Schema::create('sprites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pokemon_id');
            $table->string('front_default');
            $table->string('front_female')->nullable();
            $table->string('front_shiny');
            $table->string('front_shiny_female')->nullable();
            $table->string('back_default');
            $table->string('back_female')->nullable();
            $table->string('back_shiny');
            $table->string('back_shiny_female')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sprites');
    }
};