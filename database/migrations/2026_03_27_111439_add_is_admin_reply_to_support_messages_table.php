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
        Schema::table('support_messages', function (Blueprint $table) {
            // Añadimos una columna para saber si es respuesta del admin
            $table->boolean('is_admin_reply')->default(false)->after('is_read');
        });
    }

    public function down()
    {
        Schema::table('support_messages', function (Blueprint $table) {
            $table->dropColumn('is_admin_reply');
        });
    }

    /**
     * Reverse the migrations.
     */

};
