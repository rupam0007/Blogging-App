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
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('mediable'); // Polymorphic: belongs to post, story, etc.
            $table->string('file_path');
            $table->enum('file_type', ['image', 'video', 'document'])->default('image');
            $table->string('mime_type')->nullable();
            $table->bigInteger('file_size')->nullable(); // In bytes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
