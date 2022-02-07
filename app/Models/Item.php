<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        "date", "sale_id", "supplier", "manufacturer",
        "model", "colour", "battery", "grade",
        "issues", "cost", "imei", "selling_price",
        "customer", "sold", "hold", "discount", "tax",
        "subtotal", "profit",
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function removeSale(): bool
    {
        $this->sale_id = null;
        $this->customer = null;
        $this->sold = null;
        $this->hold = null;
        $this->discount = null;
        $this->tax = null;
        $this->subtotal = null;
        $this->profit = null;

        return $this->saveOrFail();
    }
}
