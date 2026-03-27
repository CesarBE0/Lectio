<?php

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
        Schema::table('library', function (Blueprint $table) {
            // Añadimos las columnas y las hacemos "nullable" (opcionales)
            // por si hay libros antiguos que no tengan dirección guardada.
            $table->string('address')->nullable()->after('format');
            $table->string('city')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('library', function (Blueprint $table) {
            // Si nos arrepentimos, borramos las columnas
            $table->dropColumn(['address', 'city']);
        });
    }
};
