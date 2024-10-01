<?php

use App\Models\activiteBudgetAnnuel;
use App\Models\evenement;
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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->text('libelle');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->foreignIdFor(activiteBudgetAnnuel::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('evenement_user', function (Blueprint $table) {
            $table->foreignIdFor(evenement::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->primary(['evenement_id', 'user_id']);
            $table->string('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
