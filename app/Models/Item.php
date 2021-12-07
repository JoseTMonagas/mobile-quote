<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        "date", "supplier", "manufacturer",
        "model", "colour", "battery", "grade",
        "issues", "cost", "imei", "selling_price"
    ];

    protected $casts = [
        "issues" => "array",
    ];
}
