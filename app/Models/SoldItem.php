<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SoldItem extends Model
{
    use HasFactory;
    protected $table = 'sold_items';
    protected $fillable = [
        'coustomer_name',
        'coustomer_email',
        'item_id',
        'quantity',
        'brand_id',
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

    public function scopeTopSellingItem(Builder $query)
    {
        return $query->with(['brand', 'item'])->get()
            ->groupBy('brand_id')
            ->map(function ($items) {
                $items = $items->groupBy('item_id')->map(function ($itemGroup) {
                    return [
                        'item_id' => $itemGroup->first()->item_id,
                        'total_quantity' => $itemGroup->sum('quantity'),
                        'item_name' => $itemGroup->first()->item->name ?? 'N/A'
                    ];
                });
                return $items->sortByDesc('total_quantity')->take(5)->values();
            });
    }
}
