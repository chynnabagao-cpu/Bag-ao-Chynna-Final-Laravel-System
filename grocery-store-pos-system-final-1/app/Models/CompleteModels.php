<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    protected $fillable = ['name', 'username', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];
}

class Category extends Model {
    protected $fillable = ['name'];
    public function products(): HasMany { return $this->hasMany(Product::class); }
}

class Product extends Model {
    protected $fillable = [
        'name', 'barcode', 'category_id', 'cost_price',
        'selling_price', 'stock_quantity', 'min_stock_threshold', 'expiration_date'
    ];

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }

    // Scopes for alerts
    public function scopeLowStock($query) {
        return $query->whereColumn('stock_quantity', '<=', 'min_stock_threshold');
    }
}

class Sale extends Model {
    protected $fillable = ['receipt_number', 'user_id', 'total_amount', 'payment_method', 'cash_received', 'change_amount'];
    public function items(): HasMany { return $this->hasMany(SaleItem::class); }
    public function cashier(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
}

class SaleItem extends Model {
    protected $fillable = ['sale_id', 'product_id', 'quantity', 'unit_price', 'subtotal'];
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
