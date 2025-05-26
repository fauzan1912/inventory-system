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
        Schema::create('asets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_category_id')->constrained('aset_categories')->cascadeOnDelete();
            $table->string('kode_aset')->unique();  // Contoh: AST-MED-001
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_perolehan')->nullable();
            $table->string('lokasi')->nullable(); // Lokasi penempatan
            $table->decimal('nilai_perolehan', 15, 2)->nullable(); // Harga beli
            $table->string('kondisi')->default('baik'); // baik, rusak ringan, rusak berat
            $table->boolean('aktif')->default(true); // jika aset sudah dijual/hapus
            $table->string('foto')->nullable(); // menyimpan nama file atau path gambar aset
            $table->string('barcode')->nullable(); // untuk simpan kode barcode (opsional)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asets');
    }
};
