<?php

use App\Models\activite;
use App\Models\programme;
use App\Models\site;
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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('province');
            $table->string('departement');
            $table->string('ville');
            $table->string('coordonnees_gps')->nullable();
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });

        Schema::create('activite_site', function (Blueprint $table) {
            $table->foreignIdFor(activite::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(site::class)->constrained()->cascadeOnDelete();
            $table->primary(['activite_id', 'site_id']);
        });

        
        Schema::create('programme_site', function (Blueprint $table) {
            $table->foreignIdFor(programme::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(site::class)->constrained()->cascadeOnDelete();
            $table->primary(['programme_id', 'site_id']);
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
        Schema::dropIfExists('programme_site');
    }
};
