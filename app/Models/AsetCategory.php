<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsetCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    // Relasi ke Aset (1 kategori bisa punya banyak aset)
    public function asets()
    {
        return $this->hasMany(Aset::class, 'aset_category_id');
    }
}
