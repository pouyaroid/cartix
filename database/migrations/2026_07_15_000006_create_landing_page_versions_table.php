<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_page_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_page_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('version');
            $table->string('label')->nullable(); // autosave, manual, publish
            $table->json('data');
            $table->timestamps();

            $table->index('landing_page_id');
            $table->index('version');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_page_versions');
    }
};
