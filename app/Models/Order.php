<?php

namespace App\Models;

use App\Models\Shipper;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    protected $fillable = [
        'order_date',
        'total_amount',
        'customer_id',
        'employee_id',
        'shipper_id'
    ];


    protected $casts = [
        'order_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'status' => 'string',
        'customer_id' => 'integer',
        'employee_id' => 'integer',
        'shipper_id' => 'integer',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the employee that processed the order.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the shipper that delivers the order.
     */
    public function shipper()
    {
        return $this->belongsTo(Shipper::class);
    }

    /**
     * Get the order details for the order.
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
