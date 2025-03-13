<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Translatable;
use App\Services\CurrencyConversion;

class Product extends Model
{
    use SoftDeletes, Translatable;

    protected $fillable = [
        'code', 'name', 'category_id', 'description',
        'image', 'price', 'hit', 'new', 'recommend',
        'count', 'name_en', 'description_en'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')
                    ->withPivot('count')
                    ->withTimestamps();
    }

    public function getPriceForCount()
    {
        return $this->pivot ? $this->pivot->count * $this->price : $this->price;
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    public function scopeHit($query)
    {
        return $query->where('hit', 1);
    }
    public function scopeNew($query)
    {
        return $query->where('new', 1);
    }
    public function scopeRecommend($query)
    {
        return $query->where('recommend', 1);
    }

    public function isAvailable()
    {
        return !$this->trashed() && $this->count > 0;
    }
    // Упрощенная обработка чекбоксов
    public function setNewAttribute($value)
    {
        $this->attributes['new'] = !empty($value) ? 1 : 0;
    }

    public function setHitAttribute($value)
    {
        $this->attributes['hit'] = !empty($value) ? 1 : 0;
    }

    public function setRecommendAttribute($value)
    {
        $this->attributes['recommend'] = !empty($value) ? 1 : 0;
    }

    public function isHit(): bool
    {
        return (bool) $this->hit;
    }

    public function isNew(): bool
    {
        return (bool) $this->new;
    }

    public function isRecommend(): bool
    {
        return (bool) $this->recommend;
    }

    public function getPriceAttribute($value)
    {
        return round(CurrencyConversion::convert($value), 2);
    }
}
