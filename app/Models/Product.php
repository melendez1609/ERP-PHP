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
        'supplier_id', // <-- Agregado a la asignación masiva
    ];

    public function status()
    {
        return $this->belongsTo(ProductStatus::class, 'product_status_id');
    }

    /**
     * Relación: Un producto pertenece a un proveedor.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}