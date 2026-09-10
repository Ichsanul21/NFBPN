<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_no')->unique();
            $table->foreignId('period_id')->constrained('ppdb_periods')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('jenjang')->index();
            $table->string('child_name');
            $table->date('child_birthdate');
            $table->string('gender')->nullable();
            $table->string('parent_name');
            $table->string('whatsapp');
            $table->json('answers')->nullable();
            $table->string('status')->default('terkirim')->index();
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->index(['period_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_registrations');
    }
};
