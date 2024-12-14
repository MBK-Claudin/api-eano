<?php
use App\Models\programme;
use App\Models\organisation;
use App\Models\budgetAnnuel;

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
        Schema::create('financements', function (Blueprint $table) {
            $table->id();
            $table->string('type_financement');
            $table->string('montant');
            $table->string('principale');
            $table->foreignIdFor(budgetAnnuel::class)->nullable()->constrained()->cascadeOnDelete();  // Clé étrangère vers BudgetAnuel
            $table->foreignIdFor(organisation::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(programme::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();

        });
    }

    /**PHP
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financements');
    }
};
