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
        Schema::table('library', function (Blueprint $table) {
            // Añadimos la columna quantity y por defecto valdrá 1
            $table->integer('quantity')->default(1)->after('format');
        });
    }

    public function down()
    {
        Schema::table('library', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
