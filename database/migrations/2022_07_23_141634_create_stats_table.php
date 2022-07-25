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
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pokemon_id');
            $table->integer('base_hp')->unsigned();
            $table->integer('effort_hp')->unsigned();
            $table->integer('base_attack')->unsigned();
            $table->integer('effort_attack')->unsigned();
            $table->integer('base_special_attack')->unsigned();
            $table->integer('effort_special_attack')->unsigned();
            $table->integer('base_defense')->unsigned();
            $table->integer('effort_defense')->unsigned();
            $table->integer('base_special_defense')->unsigned();
            $table->integer('effort_special_defense')->unsigned();
            $table->integer('base_speed')->unsigned();
            $table->integer('effort_speed')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stats');
    }
};