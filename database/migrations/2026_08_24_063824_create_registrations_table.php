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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('full_name', 100);
            $table->string('student_id', 20)->nullable();
            $table->string('class', 50);
            $table->string('phone_number', 15)->nullable();
            $table->text('address')->nullable();
            $table->text('join_reason');
            $table->foreignId('period_id')->constrained('periods')->onDelete('cascade');
            $table->enum('status', [
                'pending',
                'training',
                'accepted',
                'rejected',
                'cancelled',
                'cancel_requested'
            ])->default('pending');
            $table->date('registration_date');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};