<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['static', 'dynamic']);
            $table->string('title');
            $table->text('content');
            $table->string('unique_code')->unique();
            $table->string('foreground_color')->default('#000000');
            $table->string('background_color')->default('#FFFFFF');
            $table->string('gradient_from')->nullable();
            $table->string('gradient_to')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('style')->default('square');
            $table->string('shape')->default('square');
            $table->integer('size')->default(300);
            $table->string('error_correction')->default('M');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('scans_count')->default(0);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('unique_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
