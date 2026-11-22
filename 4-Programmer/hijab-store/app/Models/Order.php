<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Menandakan bahwa Primary Key bertipe string (contoh: INV-20261101-MWZS)
     * dan bukan auto-increment integer.
     */
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Field yang diizinkan untuk dikelola/diisi.
     */
    protected $guarded = [];

    /**
     * Relasi ke OrderItem (Satu Order memiliki banyak OrderItem).
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    /**
     * Relasi alias 'items' agar Panel Admin tidak mengembalikan error
     * RelationNotFoundException [items].
     */
    public function items()
    {
        return $this->orderItems();
    }

    /**
     * Relasi ke User (Pemilik pesanan).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}