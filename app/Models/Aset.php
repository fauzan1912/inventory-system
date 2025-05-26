<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'aset_category_id',
        'kode_aset',
        'nama',
        'deskripsi',
        'tanggal_perolehan',
        'lokasi',
        'nilai_perolehan',
        'kondisi',
        'aktif',
        'foto',
        'barcode',
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'aktif' => 'boolean',
        'nilai_perolehan' => 'decimal:2',
    ];

    // Relasi ke kategori aset
    public function kategori()
    {
        return $this->belongsTo(AsetCategory::class, 'aset_category_id');
    }
}
