<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    use HasFactory;

    protected $primaryKey = 'tag_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tag_id',
        'name',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'tag_id', 'tag_id');
    }
}
