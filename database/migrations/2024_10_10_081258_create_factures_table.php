<?php

use App\Models\ano;
use App\Models\contract;
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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('reference_facture');
            $table->string('type_facture');
            $table->string('couverture');
            $table->decimal('montant', 20, 2);
            $table->date('date_reception');
            $table->foreignIdFor(ano::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(contract::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
