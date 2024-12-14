<?php

use App\Models\Objectif;
use App\Models\Organisation;
use App\Models\Programme;
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
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('objectif_specifique');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->bigInteger('Budget_planifier_fcfa')->nullable();
            $table->bigInteger('Budget_planifier_us')->nullable();
            $table->bigInteger('Budget_executer_fcfa')->nullable();
            $table->bigInteger('Budget_executer_us')->nullable();
            $table->string('statut')->nullable();
            $table->string('echeance')->nullable();
            $table->string('taux_execution_physique')->nullable();
            $table->string('taux_execution_financier')->nullable();
            $table->foreignIdFor(Objectif::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('programme_user', function (Blueprint $table) {
            $table->foreignIdFor(Programme::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->primary(['programme_id', 'user_id']);
            $table->string('role')->nullable();
        });

        Schema::create('organisation_programme', function (Blueprint $table) {
            $table->foreignIdFor(Programme::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Organisation::class)->constrained()->cascadeOnDelete();
            $table->primary(['programme_id', 'organisation_id']);
            $table->string('ancrage')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmes');
        Schema::dropIfExists('programme_user');
        Schema::dropIfExists('organisation_programme');
    }
};
