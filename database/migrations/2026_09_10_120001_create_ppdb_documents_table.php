<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_documents', function (Blueprint $table) {
            $table->id();
            $table->string('jenjang')->index();
            $table->string('label');
            $table->text('deskripsi')->nullable();
            $table->boolean('wajib')->default(true);
            $table->unsignedInteger('urut')->default(0);
            $table->boolean('aktif')->default(true);
            $table->json('allowed')->nullable();
            $table->unsignedInteger('max_kb')->default(2048);
            $table->boolean('compress')->default(true);
            $table->string('visible_if_field')->nullable();
            $table->string('visible_if_operator')->nullable();
            $table->json('visible_if_value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_documents');
    }
};
