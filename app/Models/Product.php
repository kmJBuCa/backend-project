<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    protected $fillable = [
        'product_name',
        'cost_price',
        'selling_price',
        'quantity_in_stock',
        'minimum_stock_level',
        'status',
        'image',
        'barcode',
        'description',
        'brand',
        'model',
        'color',
        'size',
        'weight',
        'dimensions',
        'warranty',
        'country_of_origin',
        'supplier_id',
        'category_id'
    ];


/**
 * The attributes that should be cast.
 *
 * @var array<string, string>
 */
protected $casts = [
    'cost_price' => 'decimal:2',
    'selling_price' => 'decimal:2',
    'quantity_in_stock' => 'integer',
    'minimum_stock_level' => 'integer',
    'status' => 'string',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];

/**
 * Get the category that owns the product.
 */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the supplier that owns the product.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
