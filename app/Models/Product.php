<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'variant',
        'short_description',
        'description',
        'price',
        'weight',
        'main_image',
        'gallery',
        'ingredients',
        'nutrition_information',
        'allergen_information',
        'preparation_instructions',
        'storage_instructions',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'gallery' => 'array',
        'price' => 'decimal:2',
        'weight' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name . '-' . $product->variant);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

