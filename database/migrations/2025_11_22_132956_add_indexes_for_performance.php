<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (!$this->hasIndex('posts', 'posts_status_index')) {
                $table->index('status');
            }
            if (!$this->hasIndex('posts', 'posts_created_at_index')) {
                $table->index('created_at');
            }
            if (!$this->hasIndex('posts', 'posts_user_id_status_index')) {
                $table->index(['user_id', 'status']);
            }
            if (!$this->hasIndex('posts', 'posts_status_created_at_index')) {
                $table->index(['status', 'created_at']);
            }
        });

        Schema::table('comments', function (Blueprint $table) {
            if (!$this->hasIndex('comments', 'comments_post_id_index')) {
                $table->index('post_id');
            }
            if (!$this->hasIndex('comments', 'comments_user_id_index')) {
                $table->index('user_id');
            }
            if (!$this->hasIndex('comments', 'comments_parent_id_index')) {
                $table->index('parent_id');
            }
        });

        Schema::table('reactions', function (Blueprint $table) {
            if (!$this->hasIndex('reactions', 'reactions_type_index')) {
                $table->index('type');
            }
        });

        Schema::table('follows', function (Blueprint $table) {
            if (!$this->hasIndex('follows', 'follows_follower_id_index')) {
                $table->index('follower_id');
            }
            if (!$this->hasIndex('follows', 'follows_following_id_index')) {
                $table->index('following_id');
            }
        });

        Schema::table('bookmarks', function (Blueprint $table) {
            if (!$this->hasIndex('bookmarks', 'bookmarks_user_id_index')) {
                $table->index('user_id');
            }
            if (!$this->hasIndex('bookmarks', 'bookmarks_post_id_index')) {
                $table->index('post_id');
            }
        });

        Schema::table('notifications', function (Blueprint $table) {
            if (!$this->hasIndex('notifications', 'notifications_user_id_index')) {
                $table->index('user_id');
            }
            if (!$this->hasIndex('notifications', 'notifications_read_at_index')) {
                $table->index('read_at');
            }
            if (!$this->hasIndex('notifications', 'notifications_user_id_read_at_index')) {
                $table->index(['user_id', 'read_at']);
            }
        });

        Schema::table('stories', function (Blueprint $table) {
            if (!$this->hasIndex('stories', 'stories_user_id_index')) {
                $table->index('user_id');
            }
            if (!$this->hasIndex('stories', 'stories_expires_at_index')) {
                $table->index('expires_at');
            }
            if (!$this->hasIndex('stories', 'stories_user_id_expires_at_index')) {
                $table->index(['user_id', 'expires_at']);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!$this->hasIndex('users', 'users_username_index')) {
                $table->index('username');
            }
            if (!$this->hasIndex('users', 'users_role_index')) {
                $table->index('role');
            }
        });
    }

    private function hasIndex($table, $index)
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = '{$index}'");
        return !empty($indexes);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['post_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['parent_id']);
        });

        Schema::table('reactions', function (Blueprint $table) {
            $table->dropIndex(['type']);
        });

        Schema::table('follows', function (Blueprint $table) {
            $table->dropIndex(['follower_id']);
            $table->dropIndex(['following_id']);
        });

        Schema::table('bookmarks', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['post_id']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['read_at']);
            $table->dropIndex(['user_id', 'read_at']);
        });

        Schema::table('stories', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['expires_at']);
            $table->dropIndex(['user_id', 'expires_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['username']);
            $table->dropIndex(['role']);
        });
    }
};
