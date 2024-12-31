<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->dateTime('check_in_time');
            $table->dateTime('check_out_time')->nullable();
            $table->integer('steps')->nullable();
            $table->float('distance')->nullable(); // in meters
            $table->float('calories')->nullable();
            $table->float('heart_rate_avg')->nullable();
            $table->float('weight')->nullable(); // in kg
            $table->timestamps();

            // Ensure one check-in per student per day
            $table->unique(['student_id', 'check_in_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('check_ins');
    }
}; 