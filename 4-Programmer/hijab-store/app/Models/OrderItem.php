<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relasi utama ke Variant Produk
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    /**
     * Relasi alias 'variant' agar kode admin tidak error
     */
    public function variant()
    {
        return $this->productVariant();
    }
}