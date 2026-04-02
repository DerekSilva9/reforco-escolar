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
        Schema::table('students', function (Blueprint $table) {
            // Adicionar índices faltantes
            $table->index('team_id');
            $table->index('responsavel_id');
            $table->index('active');
            $table->index('created_at');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('student_id');
            $table->index('paid_at');
            $table->index(['student_id', 'year', 'month']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->index('student_id');
            $table->index('date');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('created_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['team_id']);
            $table->dropIndex(['responsavel_id']);
            $table->dropIndex(['active']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['student_id']);
            $table->dropIndex(['paid_at']);
            $table->dropIndex(['student_id', 'year', 'month']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['student_id']);
            $table->dropIndex(['date']);
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['email']);
        });
    }
};
