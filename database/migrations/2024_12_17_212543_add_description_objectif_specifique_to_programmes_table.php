<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('programmes', function (Blueprint $table) {
            $table->text('description_objectif_specifique')->nullable();
        });
    }

    public function down()
    {
        Schema::table('programmes', function (Blueprint $table) {
            $table->dropColumn('description_objectif_specifique');
        });
    }

};
