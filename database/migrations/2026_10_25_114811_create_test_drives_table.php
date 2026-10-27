<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('test_drives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mobil_id')->constrained('mobils')->cascadeOnDelete();
            $table->string('nama_customer');
            $table->string('email');
            $table->string('no_telp', 30);
            $table->date('tanggal');
            $table->text('catatan')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_drives');
    }
};
