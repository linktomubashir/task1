<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'first_btn_name','first_btn_link','second_btn_name', 'second_btn_link', 'right_image_url'];
    
}
