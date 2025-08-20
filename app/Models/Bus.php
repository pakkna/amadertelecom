<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $table = 'bus_list';

    protected $fillable = [
        'bus_name',
        'bus_number',
        'is_active',
    ];

    protected $casts = [
        'created_at' => "datetime:Y-m-d\ h:i:s",
        'updated_at' => "datetime:Y-m-d\ h:i:s"
    ];
}
