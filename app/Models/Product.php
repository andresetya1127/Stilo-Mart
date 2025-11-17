<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'barcode',
        'description',
        'price',
        'stock',
        'min_stock',
        'image',
        'unit',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'is_active' => 'boolean',
    ];

    // Auto-generate slug, SKU, and barcode
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'PRD-' . strtoupper(Str::random(8));
            }
            if (empty($product->barcode)) {
                $product->barcode = 'BAR-' . strtoupper(Str::random(8));
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->barcode)) {
                $product->barcode = 'BAR-' . strtoupper(Str::random(8));
            }
        });
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionItems()
    {
        return $this->morphMany(TransactionItem::class, 'item');
    }

    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'min_stock');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Accessors
    public function getIsLowStockAttribute()
    {
        return $this->stock <= $this->min_stock;
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
