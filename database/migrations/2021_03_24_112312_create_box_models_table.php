<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoxModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('box', function (Blueprint $table) {
            $table->id();
            $table->string('code_unique');
            $table->string('abreviation');
            $table->string('libelle');
            $table->integer('id_batiment');
            $table->boolean('activated')->default(1);
            $table->boolean('lock')->default(0);
            $table->integer('ordre');
            $table->integer('code_parent');
            //$table->integer('id_patient');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('box_models');
    }
}
