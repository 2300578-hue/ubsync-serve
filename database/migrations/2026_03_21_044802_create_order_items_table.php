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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            
            // Ikinakabit ang item na ito sa main 'orders' table
            // Kapag binura ang main order, mabubura din ang items nito
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            
            $table->string('product_name'); // Pangalan ng pagkain/inumin
            $table->decimal('price', 10, 2); // Presyo (e.g., 150.00)
            $table->integer('quantity'); // Ilan ang inorder
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};