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
            // Por defecto será false (no leído por el usuario)
            $table->boolean('user_read')->default(false)->after('is_admin_reply');
        });
    }

    public function down()
    {
        Schema::table('support_messages', function (Blueprint $table) {
            $table->dropColumn('user_read');
        });
    }
};
