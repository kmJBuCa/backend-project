<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $fillable = [
        'category_name',
        'description'
    ];

protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
