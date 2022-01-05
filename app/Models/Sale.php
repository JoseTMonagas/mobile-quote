<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = ["user_id", "subtotal", "discount", "flatTax", "tax", "total"];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
