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
            $table->string('format')->nullable()->after('book_id'); // Aquí guardaremos "Ebook", "Tapa Dura", etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('library', function (Blueprint $table) {
            //
        });
    }
};
