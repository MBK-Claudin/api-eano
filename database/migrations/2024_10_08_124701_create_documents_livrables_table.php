<?php

use App\Models\documentsLivrable;
use App\Models\livrable;
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
        Schema::create('documents_livrables', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_url');
            $table->foreignIdFor(livrable::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_livrables');
        Schema::dropIfExists('documents_livrable_livrable');
    }
};
