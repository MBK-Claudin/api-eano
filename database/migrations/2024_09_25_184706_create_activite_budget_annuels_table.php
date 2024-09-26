<?php

use App\Models\activiteBudgetAnnuel;
use App\Models\sousComposant;
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
        Schema::create('activite_budget_annuels', function (Blueprint $table) {
            $table->id();
            $table->string('libelle')->nullable();
            $table->decimal('budget_fcfa', 20, 2)->nullable();
            $table->decimal('budget_us', 20, 2)->nullable();
            $table->decimal('montant_decaisser', 20, 2)->nullable();
            $table->decimal('montant_restant', 20, 2)->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->date('taux_execution_physique')->nullable();
            $table->date('taux_execution_financier')->nullable();
            $table->foreignIdFor(sousComposant::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('activite_budget_annuel_user', function (Blueprint $table) {
            $table->foreignIdFor(activiteBudgetAnnuel::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->primary(['activite_budget_annuel_id', 'user_id']);
            $table->string('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activite_budget_annuels');
        Schema::dropIfExists('activite_budget_annuel_user');
    }
};
