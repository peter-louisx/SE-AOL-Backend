<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecycleRequest extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'vendor_id',
        'customer_id',
        'req_status',
        'shipping_method',
        'pickup_date',
        'notes',
        'total_pay'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id', 'id');
    }
}
