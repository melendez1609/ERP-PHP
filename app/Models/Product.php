<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'cost',
        'price',
        'stock',
        'min_stock',
        'product_status_id',
        'supplier_id',
    ];

    public function status()
    {
        return $this->belongsTo(ProductStatus::class, 'product_status_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}