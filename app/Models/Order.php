<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Mail\OrderStatusChanged;
use Illuminate\Support\Facades\Mail;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'total_amount',
        'products',
        'order_status',
        'phone'
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($order) {
            if ($order->isDirty('order_status')) {
                Mail::to($order->customer->email)->send(new OrderStatusChanged($order));
            }
        });
    }

    /**
     * Define relationships if any.
     * For example, if an order belongs to a customer:
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Define relationships if any.
     * For example, if an order has many products:
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    /**
     * Define relationships if any.
     * For example, if an order has one payment:
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
