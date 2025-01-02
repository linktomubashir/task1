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
<<<<<<< HEAD:app/Models/SoldItems.php
        'brand',
        'original_price',
        'discount_price',
=======
        'brand_id',
        'price_per_item',
>>>>>>> main:app/Models/SoldItem.php
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
