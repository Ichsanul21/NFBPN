<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_form_fields', function (Blueprint $table) {
            $table->string('section')->nullable()->after('label');
            $table->string('visible_if_field')->nullable()->after('sort_order');
            $table->string('visible_if_operator')->nullable()->after('visible_if_field');
            $table->json('visible_if_value')->nullable()->after('visible_if_operator');
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_form_fields', function (Blueprint $table) {
            $table->dropColumn(['section', 'visible_if_field', 'visible_if_operator', 'visible_if_value']);
        });
    }
};
