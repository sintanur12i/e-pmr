<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->text('exit_reason')->nullable()->after('membership_status');
            $table->timestamp('exit_requested_at')->nullable()->after('exit_reason');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['exit_reason', 'exit_requested_at']);
        });
    }
};