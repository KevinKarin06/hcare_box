<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOccupationModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('occupation', function (Blueprint $table) {
            $table->id();
            $table->string('code_unique');
            $table->unsignedBigInteger('id_box');
            $table->unsignedBigInteger('id_categorie');
            $table->integer('id_personnel');
            $table->boolean('cloturer')->default(0);
            $table->string('info_patient')->nullable();
            $table->integer('id_patient')->nullable();
            $table->enum('type_occupation', ['type1', 'type2']);
            $table->dateTime('date_entree');
            $table->dateTime('date_sortie');
            $table->string('observation');
            $table->integer('code_parent');
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
        Schema::dropIfExists('occupation_models');
    }
}
