<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        "date", "device", "colour", "battery",
        "grade", "issues", "cost",
        "imei", "selling_price", "sold"
    ];

    protected $casts = [
        "device" => "array",
        "issues" => "array",
    ];
}
