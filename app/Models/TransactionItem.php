<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Service;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'item_type',
        'item_id',
        'item_name',
        'price',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function item()
    {
        return $this->morphTo();
    }

    // Get the actual product or service
    public function getItemModelAttribute()
    {
        if ($this->item_type === 'product') {
            return Product::find($this->item_id);
        } elseif ($this->item_type === 'service') {
            return Service::find($this->item_id);
        }
        return null;
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}
