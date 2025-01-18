<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'product_id',
        'stripe_id',
        'unit_amount',
        'currency',
        'interval',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
