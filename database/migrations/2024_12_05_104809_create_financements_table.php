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
            $table->string('montant_usd');
            $table->string('statut');
            $table->foreignId('budgetAnnuel_id')->constrained('budget_annuels')->onDelete('cascade');
            $table->foreignId('organisation_id')->constrained('organisations')->onDelete('cascade');
            $table->foreignId('programme_id')->constrained('programmes')->onDelete('cascade');
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
