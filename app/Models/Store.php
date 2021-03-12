<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ["name", "address", "email"];

    /**
     * A Store can have many Users
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
