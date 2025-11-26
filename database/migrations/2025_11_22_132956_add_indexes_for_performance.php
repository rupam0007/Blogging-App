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
        Schema::table('posts', function (Blueprint $table) {
            // Laravel handles index creation automatically.
            // We removed the 'if (!hasIndex)' checks because they caused the crash.
            
            $table->index('status', 'posts_status_index');
            $table->index('created_at', 'posts_created_at_index');
            $table->index(['user_id', 'status'], 'posts_user_id_status_index');
            $table->index(['status', 'created_at'], 'posts_status_created_at_index');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index('post_id', 'comments_post_id_index');
            $table->index('user_id', 'comments_user_id_index');
            $table->index('parent_id', 'comments_parent_id_index');
        });

        Schema::table('reactions', function (Blueprint $table) {
            $table->index('type', 'reactions_type_index');
        });

        Schema::table('follows', function (Blueprint $table) {
            $table->index('follower_id', 'follows_follower_id_index');
            $table->index('following_id', 'follows_following_id_index');
        });

        Schema::table('bookmarks', function (Blueprint $table) {
            $table->index('user_id', 'bookmarks_user_id_index');
            $table->index('post_id', 'bookmarks_post_id_index');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index('user_id', 'notifications_user_id_index');
            $table->index('read_at', 'notifications_read_at_index');
            $table->index(['user_id', 'read_at'], 'notifications_user_id_read_at_index');
        });

        Schema::table('stories', function (Blueprint $table) {
            $table->index('user_id', 'stories_user_id_index');
            $table->index('expires_at', 'stories_expires_at_index');
            $table->index(['user_id', 'expires_at'], 'stories_user_id_expires_at_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('username', 'users_username_index');
            $table->index('role', 'users_role_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_status_index');
            $table->dropIndex('posts_created_at_index');
            $table->dropIndex('posts_user_id_status_index');
            $table->dropIndex('posts_status_created_at_index');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('comments_post_id_index');
            $table->dropIndex('comments_user_id_index');
            $table->dropIndex('comments_parent_id_index');
        });

        Schema::table('reactions', function (Blueprint $table) {
            $table->dropIndex('reactions_type_index');
        });

        Schema::table('follows', function (Blueprint $table) {
            $table->dropIndex('follows_follower_id_index');
            $table->dropIndex('follows_following_id_index');
        });

        Schema::table('bookmarks', function (Blueprint $table) {
            $table->dropIndex('bookmarks_user_id_index');
            $table->dropIndex('bookmarks_post_id_index');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_user_id_index');
            $table->dropIndex('notifications_read_at_index');
            $table->dropIndex('notifications_user_id_read_at_index');
        });

        Schema::table('stories', function (Blueprint $table) {
            $table->dropIndex('stories_user_id_index');
            $table->dropIndex('stories_expires_at_index');
            $table->dropIndex('stories_user_id_expires_at_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_username_index');
            $table->dropIndex('users_role_index');
        });
    }
};