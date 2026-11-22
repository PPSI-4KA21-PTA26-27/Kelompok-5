<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    // Mengizinkan semua kolom diisi (mass assignment)
    protected $guarded = ['id'];

    // Mendefinisikan relasi ke tabel product_variants
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}