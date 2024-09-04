<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter la colonne annee_id à la table evenements.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->unsignedBigInteger('annee_id')->after('institution_id');

            // Définir la clé étrangère pour annee_id
            $table->foreign('annee_id')->references('id')->on('annee_academiques')->onDelete('cascade');
        });
    }

    /**
     * Supprimer la colonne annee_id de la table evenements.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evenements', function (Blueprint $table) {
            // Supprimer la clé étrangère
            $table->dropForeign(['annee_id']);

            // Supprimer la colonne
            $table->dropColumn('annee_id');
        });
    }
};
