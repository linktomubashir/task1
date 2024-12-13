<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'amount', 'brand_id','model_id'];

    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    public function models(){
        return $this->belongsTo(Models::class ,'model_id', 'id');
    }
}
