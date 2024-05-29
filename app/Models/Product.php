<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity_in_stock',
    ];

    /**
     * Define relationships if any.
     * For example, if a product belongs to many orders:
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }
}
