<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Campaign extends Model
{
    protected $fillable = [
        'name', 'type', 'target_id', 'discount_percentage', 'start_date', 'end_date', 'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Check if the campaign is currently active.
     */
    public function getStatusAttribute()
    {
        $now = Carbon::now();
        if ($this->is_active && $now->between($this->start_date, $this->end_date->endOfDay())) {
            return 'ACTIVE';
        }
        return 'INACTIVE';
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'target_id')->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'target_id');
    }
}
