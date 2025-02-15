<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('collects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mission_id');
            $table->date('start');
            $table->date('end');
            $table->text('intitule_du_projet');
            $table->text('secteur');
            $table->text('gabon_province');
            $table->text('gabon_departement');
            $table->text('gabon_adm3');
            $table->text('documentations_liees_au_projet')->nullable();
            $table->text('coordonnees_geographiques');
            $table->decimal('coordonnees_geographiques_latitude', 10, 7);
            $table->decimal('coordonnees_geographiques_longitude', 10, 7);
            $table->decimal('coordonnees_geographiques_altitude', 8, 2)->nullable();
            $table->decimal('coordonnees_geographiques_precision', 8, 2)->nullable();
            $table->text('cout_initial_du_projet');
            $table->date('date_de_debut');
            $table->date('date_de_fin');
            $table->text('programme_strategique_du_projet');
            $table->text('ancrage');
            $table->text('ancrage_strategique');
            $table->text('ancrage_operationnel');
            $table->text('mentionnez_les_encrages_strategiques');
            $table->text('maitre_d_ouvrage');
            $table->text('maitre_d_ouvrage_delegue');
            $table->text('maitre_d_oeuvre');
            $table->text('objectifs_general');
            $table->text('objectifs_specifiques');
            $table->text('resultats_attendus');
            $table->text('unite_de_gestion_du_projet');
            $table->text('parties_prenantes');
            $table->foreign('mission_id')->references('id')->on('missions')->onDelete('cascade');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collects');
    }
};
