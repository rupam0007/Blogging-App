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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Recipient
            $table->foreignId('from_user_id')->nullable()->constrained('users')->onDelete('cascade'); // Sender
            $table->enum('type', ['follow', 'comment', 'reaction', 'mention', 'story_view']); 
            $table->morphs('notifiable'); // The related item (post, comment, story, etc.)
            $table->text('content')->nullable(); // Custom message
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
