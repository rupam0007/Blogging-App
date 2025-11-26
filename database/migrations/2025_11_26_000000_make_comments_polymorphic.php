<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedBigInteger('commentable_id')->after('user_id')->nullable();
            $table->string('commentable_type')->after('commentable_id')->nullable();
            $table->index(['commentable_type', 'commentable_id']);
        });

        DB::table('comments')->whereNotNull('post_id')->update([
            'commentable_id' => DB::raw('post_id'),
            'commentable_type' => 'App\\Models\\Post'
        ]);

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['post_id']); 
            $table->dropColumn('post_id');
        });
        
        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedBigInteger('commentable_id')->nullable(false)->change();
            $table->string('commentable_type')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('post_id')->nullable()->constrained()->onDelete('cascade');
        });

        DB::table('comments')->where('commentable_type', 'App\\Models\\Post')->update([
            'post_id' => DB::raw('commentable_id')
        ]);

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['commentable_type', 'commentable_id']);
            $table->dropColumn(['commentable_id', 'commentable_type']);
        });
    }
};