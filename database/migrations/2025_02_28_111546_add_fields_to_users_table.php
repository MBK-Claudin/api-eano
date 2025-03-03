<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('statut')->default('Actif');
            $table->string('entreprise')->nullable();
            $table->boolean('mot_de_passe_expire')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['statut', 'entreprise', 'mot_de_passe_expire']);
        });
    }
};
