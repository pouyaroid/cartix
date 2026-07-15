<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            // Eye/Corner marker styles
            $table->string('eye_style')->default('square')->after('shape');
            $table->string('eye_color')->nullable()->after('eye_style');
            
            // Frame options
            $table->string('frame_style')->nullable()->after('eye_color');
            $table->string('frame_color')->nullable()->after('frame_style');
            
            // Text options
            $table->string('text')->nullable()->after('frame_color');
            $table->string('text_position')->default('bottom')->after('text');
            $table->string('text_font')->default('Vazirmatn')->after('text_position');
            $table->string('text_size')->default('14')->after('text_font');
            $table->string('text_color')->default('#000000')->after('text_size');
            
            // Logo options
            $table->integer('logo_size')->default(50)->after('text_color');
            $table->integer('logo_padding')->default(5)->after('logo_size');
            
            // Margin/padding
            $table->integer('margin')->default(10)->after('logo_padding');
            
            // Resolution
            $table->integer('resolution')->default(300)->after('margin');
            
            // Pattern type
            $table->string('pattern')->default('default')->after('resolution');
        });
    }

    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropColumn([
                'eye_style', 'eye_color', 'frame_style', 'frame_color',
                'text', 'text_position', 'text_font', 'text_size', 'text_color',
                'logo_size', 'logo_padding', 'margin', 'resolution', 'pattern',
            ]);
        });
    }
};
