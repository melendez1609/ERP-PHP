<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    /**
     * Obtiene el estado del producto.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(ProductStatus::class, 'product_status_id');
    }
}