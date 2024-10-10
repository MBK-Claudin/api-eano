<?php

use App\Models\programme;
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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('reference_contract');
            $table->string('libelle');
            $table->string('description');
            $table->decimal('montant', 20, 2);
            $table->decimal('montant_decaisse', 20, 2)->nullable();
            $table->decimal('montant_restant', 20, 2)->nullable();
            $table->foreignIdFor(programme::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
