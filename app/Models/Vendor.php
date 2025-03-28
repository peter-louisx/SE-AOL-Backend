<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model {
    use HasFactory;

    protected $primaryKey = 'vendor_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['vendor_id', 'name', 'address', 'phone_number'];

    public function recycleRequests() {
        return $this->hasMany(RecycleRequest::class, 'vendor_id');
    }
}
