<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'reason',
        'user_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getTypeLabelAttribute()
    {
        return $this->type === 'increase' ? 'Penambahan' : 'Pengurangan';
    }

    public function getVarianceAttribute()
    {
        return $this->type === 'increase' ? $this->quantity : -$this->quantity;
    }
}
