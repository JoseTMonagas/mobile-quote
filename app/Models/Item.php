<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        "date", "sale_id", "supplier", "manufacturer",
        "model", "colour", "battery", "grade",
        "issues", "cost", "imei", "selling_price",
        "customer", "discount", "tax", "subtotal",
        "profit", "sold"
    ];

    protected $casts = [
        "issues" => "array",
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
