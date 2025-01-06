<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('livrables', function (Blueprint $table) {
            // Ajouter la colonne programme_id
            $table->unsignedBigInteger('programme_id');

            // Définir la clé étrangère pour programme_id
            $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('livrables', function (Blueprint $table) {
            // Supprimer la clé étrangère et la colonne
            $table->dropForeign(['programme_id']);
            $table->dropColumn('programme_id');
        });
    }

};
