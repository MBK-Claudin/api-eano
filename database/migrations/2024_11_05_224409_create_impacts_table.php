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
        Schema::create('impacts', function (Blueprint $table) {
            $table->id();
            $table->string('type_impact');
            $table->string('libelle_impact');
            $table->string('force');
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->string('taille');
            $table->string('mitigation');
            $table->foreignId('programme_id')->constrained('programmes')->onDelete('cascade');
            $table->foreignId('activite_budget_annuel_id')->constrained('activite_budget_annuels')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('impacts');
    }
};
