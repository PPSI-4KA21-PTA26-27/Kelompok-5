<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $guarded = ['id'];

    // Mendefinisikan relasi balik ke tabel products
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}