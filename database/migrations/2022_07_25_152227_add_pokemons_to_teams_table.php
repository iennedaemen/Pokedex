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
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('pokemon');
            $table->foreignId('pokemon_id_1');
            $table->foreignId('pokemon_id_2')->nullable();
            $table->foreignId('pokemon_id_3')->nullable();
            $table->foreignId('pokemon_id_4')->nullable();
            $table->foreignId('pokemon_id_5')->nullable();
            $table->foreignId('pokemon_id_6')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('pokemon');
            $table->dropColumn('pokemon_id_1');
            $table->dropColumn('pokemon_id_2');
            $table->dropColumn('pokemon_id_3');
            $table->dropColumn('pokemon_id_4');
            $table->dropColumn('pokemon_id_5');
            $table->dropColumn('pokemon_id_6');
        });
    }
};