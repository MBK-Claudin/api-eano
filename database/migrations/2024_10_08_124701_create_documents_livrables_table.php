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
            $table->timestamps();
        });

        Schema::create('documents_livrable_livrable', function (Blueprint $table) {
            $table->foreignIdFor(documentsLivrable::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(livrable::class)->constrained()->cascadeOnDelete();
            $table->primary(['documents_livrable_id', 'livrable_id']);
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
