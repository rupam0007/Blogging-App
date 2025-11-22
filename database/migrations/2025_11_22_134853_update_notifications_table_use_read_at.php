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
        Schema::table('notifications', function (Blueprint $table) {
            // Check if is_read column exists and convert to read_at
            if (Schema::hasColumn('notifications', 'is_read')) {
                // Update existing records: set read_at to now() where is_read = true
                DB::statement('UPDATE notifications SET created_at = created_at WHERE is_read = 1');
                
                // Add read_at column
                $table->timestamp('read_at')->nullable()->after('content');
                
                // Set read_at for read notifications
                DB::statement('UPDATE notifications SET read_at = updated_at WHERE is_read = 1');
                
                // Drop is_read column
                $table->dropColumn('is_read');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Re-add is_read column
            if (!Schema::hasColumn('notifications', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('content');
                
                // Convert read_at back to is_read
                DB::statement('UPDATE notifications SET is_read = 1 WHERE read_at IS NOT NULL');
                
                // Drop read_at column
                if (Schema::hasColumn('notifications', 'read_at')) {
                    $table->dropColumn('read_at');
                }
            }
        });
    }
};
