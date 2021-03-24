<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlerteEquipementModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alerte_equipement', function (Blueprint $table) {
            $table->id();
            $table->string('code_unique');
            $table->unsignedBigInteger('id_categorie');
            $table->unsignedBigInteger('id_equipement');
            $table->enum('type_alerte', ['type1', 'type2']);
            $table->string('description');
            $table->date('date_declaration');
            $table->date('date_cloture');
            $table->string('description_solution');
            $table->integer('code_parent');
            $table->boolean('activated')->default(1);
            $table->boolean('lock')->default(0);
            $table->integer('ordre');
            $table->timestamps();

            $table->foreign('id_equipement')
                ->references('id')
                ->on('equipement_box')
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
        Schema::dropIfExists('alerte_equipement_models');
    }
}
