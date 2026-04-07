<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            // Añadimos la columna user_id y decimos que puede ser nula
            // (por si quieres crear cupones genéricos como 'BIENVENIDA10' para todos)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->after('id');
        });
    }

    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
