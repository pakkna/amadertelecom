<?php

namespace App\Models;

use App\Models\Bus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignBus extends Model
{
    use HasFactory;

    protected $table = 'assign_bus_route_to_driver';

    protected $fillable = [
        'bus_id',
        'route_id',
        'driver_user_id',
    ];

    protected $casts = [
        'created_at' => "datetime:Y-m-d\ h:i:s",
        'updated_at' => "datetime:Y-m-d\ h:i:s"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'driver_user_id');
    }
    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }
    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }
}
