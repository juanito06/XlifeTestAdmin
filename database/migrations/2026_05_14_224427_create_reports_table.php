<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('reportable');
            $table->enum('type', ['spam', 'harassment', 'misinformation', 'nudity', 'other']);
            $table->enum('status', ['pending', 'resolved', 'dismissed'])->default('pending');
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};