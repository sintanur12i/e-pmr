<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->enum('type', ['period_completion', 'unit_completion']);
            $table->foreignId('period_id')->constrained('periods')->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('cascade');
            $table->string('title', 150);
            $table->string('file', 255);
            $table->foreignId('issued_by')->constrained('users')->onDelete('cascade');
            $table->date('issued_at');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};