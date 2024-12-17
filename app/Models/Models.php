<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Models extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['name', 'brand_id'];
    
    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function items(){
        return $this->hasMany(Item::class, 'model_id', 'id');
    }
}
