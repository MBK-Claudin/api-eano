<?php

use App\Models\activiteBudgetAnnuel;
use App\Models\programme;
use App\Models\site;
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
            $table->string('taille');
            $table->string('mitigation');
            $table->foreignIdFor(site::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(programme::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(activiteBudgetAnnuel::class)->nullable()->constrained()->cascadeOnDelete();
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
