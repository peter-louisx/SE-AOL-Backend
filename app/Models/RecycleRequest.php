<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecycleRequest extends Model
{
    use HasFactory;

    protected $primaryKey = 'request_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'vendor_id',
        'customer_id',
        'message_id',
        'req_status',
        'delivery_type',
        'total_pay',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id', 'message_id');
    }
}
