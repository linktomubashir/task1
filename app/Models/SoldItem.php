<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldItem extends Model
{
    use HasFactory;
    protected $table = 'sold_items';
    protected $fillable = [
        'coustomer_name',
        'coustomer_email',
        'item_id',
        'quantity',
        'brand',
        'original_price',
        'discount_price',
        'total_amount',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

}
