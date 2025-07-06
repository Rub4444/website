<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Translatable;

class Category extends Model
{
    use Translatable;
    protected $fillable = ['code', 'name', 'description', 'image', 'name_en', 'description_en'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function skus()
    {
        return $this->hasManyThrough(Sku::class, Product::class);
    }
}
