<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_page_form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_page_id')->constrained()->cascadeOnDelete();
            $table->string('form_id');
            $table->json('data');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index('landing_page_id');
            $table->index('is_read');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_page_form_submissions');
    }
};
