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
        Schema::create('budget_annuels', function (Blueprint $table) {
            $table->id();
            $table->string('periode');
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->string('statut')->nullable();
            $table->decimal('Budget_planifier')->nullable();
            $table->decimal('Budget_executer')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_url');
            $table->foreignIdFor(programme::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_annuels');
    }
};
