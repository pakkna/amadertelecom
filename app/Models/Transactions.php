<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Transactions extends Model
{
    use HasFactory;
    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'sender_number',
        'wallet_type',
        'transaction_number',
        'transaction_amount',
        'transaction_date',
        'status',
    ];

    public $timestamps = true; // uses created_at & updated_at


    protected $casts = [
        'user_id'           => 'integer',
        'transaction_amount' => 'decimal:2',
        'transaction_date'  => 'datetime',
    ];
}
