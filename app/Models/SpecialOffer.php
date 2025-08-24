<?php

namespace App\Models;

use App\Models\Operator;
use App\Models\Packages;
use App\Models\SpecialOfferImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpecialOffer extends Model
{
    protected $table = 'special_offers';

    protected $fillable = [
        'operator_id',
        'package_id',
        'status',
        'sort_order',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    public function package()
    {
        return $this->belongsTo(Packages::class, 'package_id');
    }

    public function images()
    {
        return $this->hasMany(SpecialOfferImage::class, 'offer_id')->orderBy('sort_order');
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }
}
