<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Quote extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ["user_id", "device_id", "value"];

    /**
     * Many to Many relationship with issues
     * @return Collection
     */
    public function issues()
    {
        return $this->belongsToMany(Issue::class);
    }

    /**
      A Device may have many quotes
      @return Collection
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }


    /**
     * An User may have many quotes
     * @return Collection
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStorePriceAttribute()
    {
        $storePercent = 0;
        if ($this->user->stores->count() > 0) {
            $storePercent = $this->user->stores->first()->pluck("price_percent");
        }

        return $this->device->base_price
            + ($this->device->base_price * $storePercent);
    }
}
