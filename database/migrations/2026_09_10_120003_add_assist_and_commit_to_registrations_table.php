<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->boolean('dibantu_tu')->default(false)->after('admin_note');
            $table->foreignId('assisted_by')->nullable()->after('dibantu_tu')->constrained('users')->nullOnDelete();
            $table->text('komitmen_teks')->nullable()->after('assisted_by');
            $table->timestamp('komitmen_at')->nullable()->after('komitmen_teks');
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assisted_by');
            $table->dropColumn(['dibantu_tu', 'komitmen_teks', 'komitmen_at']);
        });
    }
};
