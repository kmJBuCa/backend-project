<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;
    protected $fillable = [
        'customer_name',
        'contact_name',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'company',
        'website',
        'status', // active, inactive, suspended
        'customer_type', // regular, premium, vip
        'bank_name',
        'account_name',
        'account_number',
        'notes'
    ];


        protected $casts = [
            'status' => 'string',
            'customer_type' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'notes' => 'string',
        ];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
