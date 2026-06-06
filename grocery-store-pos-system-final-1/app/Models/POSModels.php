<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model {
    protected $fillable = [
        'name', 'barcode', 'category_id', 'cost_price',
        'selling_price', 'stock_quantity', 'min_stock_threshold', 'expiration_date'
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }
}

class Sale extends Model {
    protected $fillable = [
        'receipt_number', 'user_id', 'total_amount',
        'payment_method', 'cash_received', 'change_amount'
    ];

    public function items(): HasMany {
        return $this->hasMany(SaleItem::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}

class SaleItem extends Model {
    protected $fillable = [
        'sale_id', 'product_id', 'quantity', 'unit_price', 'subtotal'
    ];

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }
}
