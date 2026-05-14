<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blacklists', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['ip', 'phone']);
            $table->string('value');
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blacklists');
    }
};