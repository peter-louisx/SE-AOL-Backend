<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'author',
        'publish_date',
        'description',
        'picture',
    ];

    protected $casts = [
        'description' => 'array',
        'picture' => 'array',
        'publish_date' => 'date',
    ];
}
