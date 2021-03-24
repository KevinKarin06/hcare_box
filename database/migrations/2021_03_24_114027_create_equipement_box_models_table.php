<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipementBoxModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipement_box', function (Blueprint $table) {
            $table->id();
            $table->string('code_unique');
            $table->unsignedBigInteger('id_categorie');
            $table->unsignedBigInteger('id_box');
            $table->string('libelle');
            $table->string('numero_serie');
            $table->string('description');
            $table->timestamps();

            $table->foreign('id_box')
                ->references('id')
                ->on('box')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->foreign('id_categorie')
                ->references('id')
                ->on('categorie_eqp_alt_occ')
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipement_box_models');
    }
}
