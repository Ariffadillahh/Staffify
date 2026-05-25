<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proker_id')->constrained('prokers')->cascadeOnDelete();
            $table->foreignId('kadiv_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_divisi');
            $table->integer('kuota_staff')->default(0);
            $table->boolean('is_open')->default(false);
            $table->string('link_wawancara')->nullable();
            $table->string('grup_link')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divises');
    }
};
