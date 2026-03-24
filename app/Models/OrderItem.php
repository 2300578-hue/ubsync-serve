<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // ITO ANG LAMAN NA DAPAT MONG ILAGAY:
    protected $fillable = [
        'order_id',
        'product_name',
        'price',
        'quantity',
    ];

    // Para malaman ng item kung saang Order siya belong
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}