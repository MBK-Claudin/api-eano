<?php

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
        Schema::create('document_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_url');
            $table->foreignIdFor(contract::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_contracts');
    }
};
