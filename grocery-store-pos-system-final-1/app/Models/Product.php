<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'barcode', 'image_path', 'category_id', 'cost_price',
        'selling_price', 'discount_percentage', 'stock_quantity', 'min_stock_threshold', 'expiration_date'
    ];

    /**
     * Get the discounted price of the product.
     */
    public function getDiscountedPriceAttribute()
    {
        return $this->selling_price - ($this->selling_price * ($this->discount_percentage / 100));
    }

    protected $casts = [
        'expiration_date' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'min_stock_threshold');
    }
}
