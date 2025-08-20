<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'location';

    protected $fillable = [
        'lat',
        'long',
        'bus_id',
        'route_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => "datetime:Y-m-d\ h:i:s",
        'updated_at' => "datetime:Y-m-d\ h:i:s"
    ];
}
