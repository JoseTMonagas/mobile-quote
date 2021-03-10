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

    protected $fillable = ["device_id", "value"];

    /**
     * Many to Many relationship with issues
     * @return Collection
     */
    public function issues()
    {
        return $this->belongsToMany(Issue::class);
    }

    /** A Device may have many quotes */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
