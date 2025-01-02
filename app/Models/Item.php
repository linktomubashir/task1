<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'amount', 'brand_id', 'model_id', 'image', 'quantity', 'status'];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function models()
    {
        return $this->belongsTo(Models::class, 'model_id', 'id');
    }

    public function getEffectivePrice()
    {
        $originalPrice = $this->amount;
        $finalPrice = $originalPrice;

        $brandItemCount = $this->brand ? $this->brand->items()->count() : 0;
        if ($brandItemCount > 10) {
            $finalPrice = $finalPrice - ($finalPrice * 0.10);
        }
        if ($this->quantity < 5 && $this->quantity >= 1) {
            $finalPrice = $finalPrice - ($finalPrice * 0.06);
        }
        return round($finalPrice, 2);
    }
}
