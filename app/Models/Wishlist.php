<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = ['user_id', 'sku_id'];

    public function sku()
    {
        return $this->belongsTo(Sku::class)->withTrashed(); // <--- важно!
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
