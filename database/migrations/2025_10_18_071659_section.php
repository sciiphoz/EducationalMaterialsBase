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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['text', 'code', 'image']);
            $table->text('content')->nullable(); // Для текста и кода
            $table->longText('image_base64')->nullable(); // Base64 encoded image
            $table->string('image_mime_type')->nullable(); // MIME тип
            $table->string('image_name')->nullable(); // Оригинальное имя файла
            $table->string('image_alt')->nullable(); // Alt текст
            $table->string('language')->nullable(); // Для секции кода
            $table->integer('order')->default(0);
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
