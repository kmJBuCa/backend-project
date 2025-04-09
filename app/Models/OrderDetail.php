<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetail extends Model
{
    /** @use HasFactory<\Database\Factories\OrderDetailFactory> */
    use HasFactory;
    protected $fillable = [
        'quantity',
        'price',
        'subtotal',
        'order_id',
        'product_id',
    ];

  
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that belongs to the order detail.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
