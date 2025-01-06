<?php

use App\Models\Objectif;
use App\Models\Organisation;
use App\Models\User;
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
        Schema::create('objectifs', function (Blueprint $table) {
            $table->id();
            $table->text('objectif');
            $table->string('secteur');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->string('description')->nullable();
            $table->string('echeance')->nullable();
            $table->decimal('taux_execution_physique')->nullable();
            $table->decimal('taux_execution_final')->nullable();
            $table->timestamps();
        });

        Schema::create('objectif_user', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Objectif::class)->nullable()->constrained()->cascadeOnDelete();
            $table->primary(['objectif_id', 'user_id']);
            $table->string('role');
        });

        Schema::create('objectif_organisation', function (Blueprint $table) {
            $table->foreignIdFor(Organisation::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Objectif::class)->nullable()->constrained()->cascadeOnDelete();
            $table->primary(['objectif_id', 'organisation_id']);
            $table->string('ancrage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objectif_organisation');
        Schema::dropIfExists('objectif_user');
        Schema::dropIfExists('objectifs');
    }
};
