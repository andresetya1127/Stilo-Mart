<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'code',
        'description',
        'price',
        'duration',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'integer',
        'is_active' => 'boolean',
    ];

    // Auto-generate slug and code
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->name);
            }
            if (empty($service->code)) {
                $service->code = 'SRV-' . strtoupper(Str::random(8));
            }
        });

        static::updating(function ($service) {
            if ($service->isDirty('name')) {
                $service->slug = Str::slug($service->name);
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) {
            return '-';
        }

        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours} jam {$minutes} menit";
        } elseif ($hours > 0) {
            return "{$hours} jam";
        } else {
            return "{$minutes} menit";
        }
    }
}
