<?php

use App\Models\ano;
use App\Models\documentAno;
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
        Schema::create('document_anos', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_url');
            $table->timestamps();
        });

        Schema::create('ano_document_ano', function (Blueprint $table) {
            $table->foreignIdFor(ano::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(documentAno::class)->constrained()->cascadeOnDelete();
            $table->primary(['ano_id', 'document_ano_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_anos');
    }
};
