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
        Schema::create('mobils', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('brand', 100);
            $table->bigInteger('harga');
            $table->smallInteger('tahun');
            $table->enum('transmisi', ['Manual', 'Matic', 'CVT']);
            $table->enum('bahan_bakar', ['Bensin', 'Diesel', 'Hybrid', 'Listrik']);
            $table->enum('status', ['tersedia', 'sold'])->default('tersedia');
            $table->text('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobils');
    }
};
