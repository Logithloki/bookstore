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
        Schema::create('api_rate_limits', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable(); // User ID if authenticated
            $table->string('ip_address')->nullable(); // IP address for tracking
            $table->string('endpoint'); // API endpoint being accessed
            $table->integer('attempts')->default(1); // Number of attempts
            $table->timestamp('window_start'); // Start of the rate limit window
            $table->string('window_type'); // Type of window: minute, hour, day
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'endpoint', 'window_start']);
            $table->index(['ip_address', 'endpoint', 'window_start']);
            $table->index(['window_start']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_rate_limits');
    }
};
