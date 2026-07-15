<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_page_styles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_page_id')->constrained()->cascadeOnDelete();
            $table->enum('scope', ['page', 'global'])->default('page');
            $table->string('selector');
            $table->json('properties');
            $table->enum('breakpoint', ['desktop', 'tablet', 'mobile'])->default('desktop');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('landing_page_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_page_styles');
    }
};
