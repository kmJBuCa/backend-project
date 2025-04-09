<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'contact_person',
        'phone',
        'email',
        'website',
        'bio',
        'address',
        'city',
        'country',
        'brand_name',
        'active',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'logo'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    /**
     * Get the products for the supplier.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
