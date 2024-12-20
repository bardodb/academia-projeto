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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone');
            $table->date('birth_date');
            $table->string('emergency_contact');
            $table->string('emergency_phone');
            $table->text('health_conditions')->nullable();
            $table->foreignId('plan_id')->constrained();
            $table->date('plan_start_date');
            $table->date('plan_end_date');
            $table->decimal('monthly_fee', 10, 2);
            $table->integer('payment_day');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
