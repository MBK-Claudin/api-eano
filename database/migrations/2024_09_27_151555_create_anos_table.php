<?php

use App\Models\activiteBudgetAnnuel;
use App\Models\ano;
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
        Schema::create('anos', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->decimal('budget', 20, 2)->default(0);
            $table->decimal('budget_cntippee', 20, 2)->nullable()->default(0);
            $table->string('statut')->nullable();
            $table->text('situation_actuelle')->nullable();
            $table->text('situation_venir')->nullable();
            $table->text('commentaire')->nullable();
            $table->foreignIdFor(activiteBudgetAnnuel::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('ano_user', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ano::class)->constrained()->cascadeOnDelete();
            $table->string('action')->nullable();
            $table->string('role')->nullable();
        });

        Schema::table('evenements', function (Blueprint $table) {
            $table->foreignIdFor(ano::class)->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anos');
        Schema::dropIfExists('ano_user');
    }
};
