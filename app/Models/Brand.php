<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $primaryKey = 'brand_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'brand_id',
        'name',
        'description',
    ];

    public function sellers()
    {
        return $this->hasMany(Seller::class, 'brand_id', 'brand_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id', 'brand_id');
    }
}
