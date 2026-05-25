<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proker_id')->constrained('prokers')->cascadeOnDelete();

            // Data Diri
            $table->string('nama_lengkap');
            $table->string('nim');
            $table->string('email');
            $table->string('no_whatsapp');

            // Pilihan Divisi & Alasan
            $table->foreignId('divisi_id')->constrained('divisis')->cascadeOnDelete();
            $table->text('alasan');
            $table->boolean('bersedia_pindah_divisi')->default(false);
            $table->text("alasan_mengikuti_proker")->nullable();

            // Berkas & Jadwal
            $table->text('pengalaman')->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('jadwal_wawancara_id')->nullable()->constrained('jadwal_wawancaras')->nullOnDelete();

            $table->string('status')->default('pending');
            $table->float('nilai_akhir')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
