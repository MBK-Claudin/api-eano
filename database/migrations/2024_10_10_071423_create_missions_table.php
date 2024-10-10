<?php

use App\Models\activite;
use App\Models\mission;
use App\Models\site;
use App\Models\User;
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
            $table->text('description');
            $table->text('objectif');
            $table->timestamps();
        });

        Schema::create('mission_user', function (Blueprint $table) {
            $table->foreignIdFor(mission::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('role');
            $table->primary(['user_id', 'mission_id']);
        });

        Schema::create('activite_mission', function (Blueprint $table) {
            $table->foreignIdFor(mission::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(activite::class)->constrained()->cascadeOnDelete();
            $table->primary(['activite_id', 'mission_id']);
        });

        Schema::create('mission_site', function (Blueprint $table) {
            $table->foreignIdFor(mission::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(site::class)->constrained()->cascadeOnDelete();
            $table->primary(['site_id', 'mission_id']);
        });

        Schema::create('mission_livrable', function (Blueprint $table) {
            $table->foreignIdFor(mission::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(site::class)->constrained()->cascadeOnDelete();
            $table->primary(['livrable_id', 'mission_id']);
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missions');
        Schema::dropIfExists('mission_user');
        Schema::dropIfExists('activite_mission');
        Schema::dropIfExists('mission_site');
        Schema::dropIfExists('mission_livrable');
    }
};
