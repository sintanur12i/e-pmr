<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE members MODIFY membership_status ENUM('active','inactive','pending_exit') NOT NULL DEFAULT 'active'");
        DB::statement("ALTER TABLE member_units MODIFY status ENUM('pending','approved','rejected','exit_requested','left') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE members MODIFY membership_status ENUM('active','inactive') NOT NULL DEFAULT 'active'");
        DB::statement("ALTER TABLE member_units MODIFY status ENUM('pending','approved','rejected') NOT NULL");
    }
};