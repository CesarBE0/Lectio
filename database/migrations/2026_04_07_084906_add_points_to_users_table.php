<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Añadimos la columna de puntos. Asumo que tienes 'welcome_coupon_used' del paso anterior.
            // Si te da error porque no la encuentra, cambia ->after('welcome_coupon_used') por ->after('role')
            $table->integer('points')->default(0)->after('welcome_coupon_used');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('points');
        });
    }
};
