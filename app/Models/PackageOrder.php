<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageOrder extends Model
{
    use HasFactory;

    protected $table = 'package_orders';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true; // uses created_at & updated_at

    protected $fillable = [
        'order_number',
        'package_id',
        'request_number',
        'order_amount',
        'order_status',
        'order_date',
        'user_id',
        'order_info',
        'order_note',
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'order_date'   => 'date',
        'order_info'   => 'array',   // json <-> array
    ];
}
