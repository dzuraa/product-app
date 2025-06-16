<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'admin_id',
        'description',
        'price',
        'product_image',
        'start_date',
        'end_date',
        'status',
        'stock'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
