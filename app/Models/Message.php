<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $primaryKey = 'message_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'to_number',
        'from_number',
        'message_text',
        'sent_date',
    ];
}
