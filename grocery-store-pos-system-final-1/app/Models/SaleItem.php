<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    protected $fillable = ['sale_id', 'product_id', 'quantity', 'original_price', 'unit_price', 'discount_amount', 'subtotal'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
