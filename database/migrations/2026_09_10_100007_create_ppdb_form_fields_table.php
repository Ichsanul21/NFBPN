<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_form_fields', function (Blueprint $table) {
            $table->id();
            $table->string('jenjang')->index();
            $table->string('key');
            $table->string('label');
            $table->string('type')->default('text');
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_core')->default(false);
            $table->timestamps();

            $table->unique(['jenjang', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_form_fields');
    }
};
