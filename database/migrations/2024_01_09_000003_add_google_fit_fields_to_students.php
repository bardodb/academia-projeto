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
            $table->string('google_token')->nullable();
            $table->string('google_refresh_token')->nullable();
            $table->json('google_fit_data')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->boolean('google_connected')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'google_token',
                'google_refresh_token',
                'google_fit_data',
                'last_sync_at',
                'google_connected'
            ]);
        });
    }
}; 