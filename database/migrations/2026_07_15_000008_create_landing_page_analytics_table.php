<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_page_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_page_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('unique_views')->default(0);
            $table->json('referrers')->nullable();
            $table->json('devices')->nullable();
            $table->json('locations')->nullable();
            $table->timestamps();

            $table->unique(['landing_page_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_page_analytics');
    }
};
