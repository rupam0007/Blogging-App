<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations to make the comments table polymorphic.
     */
    public function up(): void
    {
        // 1. Add the new polymorphic columns (commentable_id and commentable_type)
        // We check for existence to prevent the "duplicate column name" error on failed retries.
        Schema::table('comments', function (Blueprint $table) {
            if (!Schema::hasColumn('comments', 'commentable_id')) {
                // Add nullable columns first to allow populating data
                $table->unsignedBigInteger('commentable_id')->after('user_id')->nullable();
                $table->string('commentable_type')->after('commentable_id')->nullable();
                $table->index(['commentable_type', 'commentable_id']);
            }
        });

        // 2. Migrate existing data from old 'post_id' to new polymorphic columns
        if (Schema::hasColumn('comments', 'post_id')) {
            DB::table('comments')
                ->whereNotNull('post_id')
                ->update([
                    'commentable_id' => DB::raw('post_id'),
                    // Assume all existing comments were on a Post model
                    'commentable_type' => 'App\\Models\\Post'
                ]);

            // 3. Drop the old foreign key and column
            Schema::table('comments', function (Blueprint $table) {
                // Safely drop the foreign key constraint first
                // Note: The specific name for the foreign key might vary if you are not using Laravel conventions
                // We'll try the conventional drop for post_id
                try {
                    $table->dropForeign(['post_id']);
                } catch (\Exception $e) {
                    // Ignore if foreign key doesn't exist or name is incorrect
                }
                
                $table->dropColumn('post_id');
            });
        }
        
        // 4. Make the polymorphic columns NOT NULLABLE now that data has been migrated
        // NOTE: This step requires the 'doctrine/dbal' package.
        Schema::table('comments', function (Blueprint $table) {
            // We only need to attempt the change if the columns were created in step 1
            if (Schema::hasColumn('comments', 'commentable_id')) {
                $table->unsignedBigInteger('commentable_id')->nullable(false)->change();
                $table->string('commentable_type')->nullable(false)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Recreate the old post_id column if it doesn't exist
        if (!Schema::hasColumn('comments', 'post_id')) {
            Schema::table('comments', function (Blueprint $table) {
                // Re-add as nullable first
                $table->foreignId('post_id')->nullable()->constrained()->onDelete('cascade');
            });

            // 2. Reverse migrate data: from polymorphic to post_id
            DB::table('comments')
                ->where('commentable_type', 'App\\Models\\Post')
                ->update([
                    'post_id' => DB::raw('commentable_id')
                ]);
        }

        // 3. Drop the polymorphic columns and index
        Schema::table('comments', function (Blueprint $table) {
            if (Schema::hasColumn('comments', 'commentable_id')) {
                $table->dropIndex(['commentable_type', 'commentable_id']);
                $table->dropColumn(['commentable_id', 'commentable_type']);
            }
        });

        // 4. Make post_id required again (requires doctrine/dbal)
        if (Schema::hasColumn('comments', 'post_id')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->foreignId('post_id')->nullable(false)->change();
            });
        }
    }
};