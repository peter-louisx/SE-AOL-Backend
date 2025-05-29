<?php

namespace App\Models;

use App\Models\Category;
use App\Models\RecycleRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model {
    use HasFactory;


    protected $fillable = [
        'name', 
        'address', 
        'phone_number'
    ];

    public function recycleRequests() {
        return $this->hasMany(RecycleRequest::class, 'vendor_id');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

}
