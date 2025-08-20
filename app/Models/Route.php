<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $table = 'route_list';

    protected $fillable = [
        'route_name',
        'route_code',
        'route_details',
        'start_time_slot',
        'departure_time_slot',
        'route_map_url'
    ];

    protected $casts = [
        'created_at' => "datetime:Y-m-d\ h:i:s",
        'updated_at' => "datetime:Y-m-d\ h:i:s"
    ];
}
