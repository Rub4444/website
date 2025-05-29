<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password', // ← добавлено обязательно
        'phone',
        'delivery_address',
        'latitude',
        'longitude',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->is_admin == 1;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlist()
    {
        return $this->belongsToMany(Sku::class, 'wishlists')->withTimestamps();
    }


    public function hasInWishlist($skuId)
    {
        return $this->wishlist()->where('sku_id', $skuId)->exists();
    }


}
