<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Listahan ng mga columns na pwedeng lagyan ng data
    protected $fillable = [
        'guest_name',
        'table_number',
        'payment_method',
        'total_price',
        'status',
    ];

    // Relationship para sa Order Items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}