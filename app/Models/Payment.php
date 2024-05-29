<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'MerchantRequestID',
        'CheckoutRequestID',
        'ResponseCode',
        'ResponseDescription',
        'CustomerMessage',
        'ResultCode',
        'ResultDesc',
        'Amount',
        'MpesaReceiptNumber',
        'Balance',
        'TransactionDate',
        'PhoneNumber'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
