<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permission', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->foreignId('member_id')->nullable()->change()->constrained('members')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('permission', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->foreignId('member_id')->nullable(false)->change()->constrained('members')->onDelete('cascade');
        });
    }
};