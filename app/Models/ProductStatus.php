<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductStatus extends Model
{
    use HasFactory;

    protected $table = 'product_statuses';

    protected $fillable = [
        'name',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_status_id');
    }
}