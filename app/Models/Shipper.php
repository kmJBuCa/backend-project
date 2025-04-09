<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipper extends Model
{
    /** @use HasFactory<\Database\Factories\ShipperFactory> */
    use HasFactory;
    protected $fillable = [
        'shipper_name',
        'contact_person',
        'phone',
        'address',
        'shipping_methods',
        'email',
        'notes',
    ];
protected $casts = [
    'shipping_methods' => 'array',
];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
