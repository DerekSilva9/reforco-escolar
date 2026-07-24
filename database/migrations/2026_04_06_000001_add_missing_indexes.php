<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add missing indexes for better query performance
     */
    public function up(): void
    {
        // Notices table indexes
        if (Schema::hasTable('notices')) {
            Schema::table('notices', function (Blueprint $table) {
                $table->index('created_by');
                $table->index('published_at');
                $table->index('pinned');
            });
        }

        // Add composite indexes for common queries
        Schema::table('students', function (Blueprint $table) {
            // For filtering active students by team
            $table->index(['team_id', 'active']);
        });

        Schema::table('payments', function (Blueprint $table) {
            // For monthly payment queries
            $table->index(['year', 'month', 'paid_at']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            // For attendance range queries
            $table->index(['student_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notices')) {
            Schema::table('notices', function (Blueprint $table) {
                $table->dropIndex(['created_by']);
                $table->dropIndex(['published_at']);
                $table->dropIndex(['pinned']);
            });
        }

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['team_id', 'active']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['year', 'month', 'paid_at']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['student_id', 'date']);
        });
    }
};
