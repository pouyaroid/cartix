<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn(['reject_reason', 'approved_at', 'rejected_at']);
        });

        // Change enum from draft/pending/approved/rejected to just draft/active
        DB::statement("ALTER TABLE cards MODIFY COLUMN status ENUM('draft','active') DEFAULT 'active'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE cards MODIFY COLUMN status ENUM('draft','pending','approved','rejected') DEFAULT 'draft'");

        Schema::table('cards', function (Blueprint $table) {
            $table->string('reject_reason')->nullable()->after('settings');
            $table->timestamp('approved_at')->nullable()->after('reject_reason');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
        });
    }
};
