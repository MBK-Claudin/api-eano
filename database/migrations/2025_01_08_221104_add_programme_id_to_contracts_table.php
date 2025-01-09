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
        Schema::table('contracts', function (Blueprint $table) {
            $table->unsignedBigInteger('programme_id')->nullable(); // Colonne clé étrangère
            $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('cascade'); // Clé étrangère
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['programme_id']); // Supprimer la contrainte de clé étrangère
            $table->dropColumn('programme_id'); // Supprimer la colonne
        });
    }
};
