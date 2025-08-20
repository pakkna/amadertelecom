<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Packages extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration',
        'operator_id',
        'category_id',
        'actual_price',
        'offer_price',
        'tag',
        'status',
    ];

    protected $casts = [
        'created_at' => "datetime:Y-m-d\ h:i:s",
        'updated_at' => "datetime:Y-m-d\ h:i:s"
    ];

    // Define the relationship
    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
