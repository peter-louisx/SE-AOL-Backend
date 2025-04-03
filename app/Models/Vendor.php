<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
