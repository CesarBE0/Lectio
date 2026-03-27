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
            // Guardamos el precio original y el dinero descontado
            $table->decimal('price', 8, 2)->nullable()->after('city');
            $table->decimal('discount', 8, 2)->default(0)->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('library', function (Blueprint $table) {
            $table->dropColumn(['price', 'discount']);
        });
    }
};
