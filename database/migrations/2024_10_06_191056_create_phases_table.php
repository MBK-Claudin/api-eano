<?php

use App\Models\activiteBudgetAnnuel;
use App\Models\phase;
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
        Schema::create('phases', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->timestamps();
        });

        DB::table('phases')->insert([
            ['libelle' => 'Initiation'],
            ['libelle' => 'Planification'],
            ['libelle' => 'Exécution'],
            ['libelle' => 'Mise en service'],
            ['libelle' => 'Clôture']
        ]);

        Schema::table('activites', function (Blueprint $table) {
            $table->foreignIdFor(phase::class)->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phases');
    }
};
