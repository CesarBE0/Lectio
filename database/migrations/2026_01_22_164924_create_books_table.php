<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('category')->nullable();
            $table->integer('pages')->nullable();
            $table->string('image_url');
            $table->boolean('is_bestseller')->default(false);
            $table->text('synopsis')->nullable();
            $table->string('publisher')->nullable();
            $table->string('language')->nullable();
            $table->string('published_date')->nullable();
            $table->decimal('rating', 3, 1)->default(0);
            $table->integer('reviews_count')->default(0);
            $table->decimal('old_price', 8, 2)->nullable(); // Para el tachado
            $table->string('discount_percent')->nullable(); // Para la etiqueta roja
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
};
