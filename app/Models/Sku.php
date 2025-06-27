<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\CurrencyConversion;

class Sku extends Model
{
    use SoftDeletes;

    protected $fillable = ['product_id', 'count', 'price', 'image'];

    protected $visible = ['id', 'count', 'price', 'product_name'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function relatedSkus($limit = 4)
    {
        return Sku::where('product_id', $this->product_id)
                ->where('id', '!=', $this->id)
                ->take($limit)
                ->get();
    }

    public function scopeAvailable($query)
    {
        return $query->where('count', '>', 0);
    }

    public function propertyOptions()
    {
        return $this->belongsToMany(PropertyOption::class, 'sku_property_option')->withTimestamps();
    }

    public function isAvailable()
    {
        return !$this->product->trashed() && $this->count > 0;
    }

    public function getPriceForCount()
    {
        return $this->pivot ? $this->pivot->count * $this->price : $this->price;
    }
    public function getProductNameAttribute()
    {
        return $this->product->name;
    }

    protected static function booted()
{
    static::updated(function ($sku) {
        // Проверяем: было <= 0, стало > 0
        if ($sku->wasChanged('count') && $sku->count > 0) {
            $original = $sku->getOriginal('count');
            if ($original <= 0) {
                \App\Models\Subscription::sendEmailsBySubscription($sku);
            }
        }
    });
}

}
