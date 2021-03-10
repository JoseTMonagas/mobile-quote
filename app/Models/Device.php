<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "model", "brand", "image", "base_price", "excellent_factor",
        "good_factor", "acceptable_factor", "broken_factor",
    ];

    /**
    * Many to Many relationship with Issues
    * @return Illuminate\Support\Collection
    */
    public function issues()
    {
        return $this->belongsToMany(Issue::class)
                    ->withPivot("deduction");
    }
}
