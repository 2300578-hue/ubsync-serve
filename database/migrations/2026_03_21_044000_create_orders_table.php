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
            $table->id(); // Primary Key
            
            // Guest & Table Info
            $table->string('guest_name');
            $table->string('table_number');
            
            // Payment & Total
            $table->string('payment_method')->default('Cash');
            $table->decimal('total_price', 10, 2);
            
            // Status Tracking (Importante para sa Cashier at Chef)
            // Default ay 'PENDING' hangga't hindi pa nababayaran
            $table->string('status')->default('PENDING'); 
            
            $table->timestamps(); // created_at at updated_at
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