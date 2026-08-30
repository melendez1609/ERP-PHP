<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'image',
        'cost',
        'price',
        'stock',
        'min_stock',
        'vat_id',
        'product_status_id',
        'supplier_id',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(ProductStatus::class, 'product_status_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function vat(): BelongsTo
    {
        return $this->belongsTo(Vat::class);
    }

    public function profitMargin(): HasOne
    {
        return $this->hasOne(ProfitMargin::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }

    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }
}