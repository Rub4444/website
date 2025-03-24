<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Translatable;

class Property extends Model
{
    use SoftDeletes, Translatable;

    protected $fillable = ['name', 'name_en'];

    public function propertyOptions()
    {
        return $this->hasMany(PropertyOption::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
