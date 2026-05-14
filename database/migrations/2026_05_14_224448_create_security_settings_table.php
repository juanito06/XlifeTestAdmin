<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('two_factor_required')->default(false);
            $table->boolean('ip_blocking_enabled')->default(true);
            $table->unsignedInteger('max_login_attempts')->default(5);
            $table->unsignedInteger('session_timeout_minutes')->default(60);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_settings');
    }
};