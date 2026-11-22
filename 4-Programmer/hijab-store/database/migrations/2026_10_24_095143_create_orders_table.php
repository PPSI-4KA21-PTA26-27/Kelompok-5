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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id')->primary(); // Format ID manual: INV-2026...
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Null jika Guest
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->integer('city_id'); // ID Kota dari RajaOngkir
            $table->string('courier'); // JNE, TIKI, dll
            $table->decimal('shipping_cost', 10, 2);
            $table->decimal('total_amount', 12, 2); // Harga produk + Ongkir
            $table->enum('payment_method', ['midtrans', 'manual_bank']);
            $table->enum('payment_status', ['unpaid', 'paid', 'expired'])->default('unpaid');
            $table->enum('order_status', ['pending', 'dikemas', 'diantar', 'selesai', 'dibatalkan'])->default('pending');
            $table->string('tracking_number')->nullable(); // Resi Kurir
            $table->string('snap_token')->nullable(); // Token popup Midtrans
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
