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
        Schema::create('enquetes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mission_id'); // Référence à la table missions
            $table->text('intitule_projet')->nullable();
            $table->text('ministere')->nullable();
            $table->text('gabon_province')->nullable();
            $table->text('gabon_departement')->nullable();
            $table->text('gabon_adm3')->nullable();
            $table->text('observations')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->decimal('altitude', 10, 2)->nullable();
            $table->decimal('precision', 10, 2)->nullable();
            $table->decimal('cout_initial', 15, 2)->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->text('photo_url')->nullable();
            $table->text('video_url')->nullable();
            $table->timestamps();

            // Clé étrangère
            $table->foreign('mission_id')->references('id')->on('missions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquetes');
    }
};
