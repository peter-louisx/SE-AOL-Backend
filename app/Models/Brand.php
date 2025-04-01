<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rating',
        'description',
    ];

    public function sellers()
    {
        return $this->hasMany(Seller::class, 'brand_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id', 'id');
    }
}
