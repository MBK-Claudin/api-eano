<?php

use App\Models\activite;
use App\Models\livrable;
use App\Models\mission;
use App\Models\site;
use App\Models\User;
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
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('description');
            $table->string('objectif');
            $table->date('date_debut');
            $table->string('statut');
            $table->foreignId('programme_id')->constrained('programmes')->onDelete('cascade');
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->foreignId('activite_id')->constrained('activites')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missions');

    }
};
