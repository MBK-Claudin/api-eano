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
        Schema::table('anos', function (Blueprint $table) {
            $table->foreignId('programme_id')->nullable()->constrained('programmes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('anos', function (Blueprint $table) {
            $table->dropForeign(['programme_id']);
            $table->dropColumn('programme_id');
        });
    }
};
