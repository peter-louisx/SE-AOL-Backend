<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'sold',
        'stock',
        'description',
        'rating',
        'tag_id',
        'category_id',
        'brand_id',
        'product_url'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tag()
    {
        return $this->belongsTo(ProductTag::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
