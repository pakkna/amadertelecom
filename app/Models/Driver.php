<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'driver_info';

    protected $fillable = [
        'driver_name',
        'primary_contact',
        'license_number',
        'license_photo',
        'address',
        'date_of_birth',
        'sex',
        'nid_number',
        'nid_photo',
        'user_id',
        'is_sign_in',
    ];

    protected $casts = [
        'created_at' => "datetime:Y-m-d\ h:i:s",
        'updated_at' => "datetime:Y-m-d\ h:i:s"
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
