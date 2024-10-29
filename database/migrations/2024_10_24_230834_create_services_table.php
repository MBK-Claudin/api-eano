<?php

use App\Models\facture;
use App\Models\service;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->timestamps();
        });

        DB::table('services')->insert([
            ['libelle' => 'Secrétariat'],
            ['libelle' => 'Responsable Administratif et Financier'],
            ['libelle' => 'Passation des Marchés'],
            ['libelle' => 'Coordonnateur Fiducier'],
            ['libelle' => 'Comptabilité']
        ]);

        Schema::create('facture_service', function (Blueprint $table) {
            $table->foreignIdFor(facture::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(service::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('etape');
            $table->timestamps();
        });

        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
        Schema::dropIfExists('facture_service');
    }
};
