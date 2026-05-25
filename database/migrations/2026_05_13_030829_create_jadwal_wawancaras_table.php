<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('jadwal_wawancaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('divisi_id')->constrained('divisis')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('waktu_mulai'); 
            $table->time('waktu_selesai');
            $table->enum('status', ['tersedia', 'tidak_tersedia', 'dibooking'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_wawancaras');
    }
};
