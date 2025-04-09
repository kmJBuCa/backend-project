<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'position',
        'department',
        'hire_date',
        'phone',
        'email',
        'address',
        'photo',
        'gender'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'gender' => 'string',
    ];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
