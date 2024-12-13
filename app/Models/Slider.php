<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $table = 'sliders';

    protected $fillable = [
        'slide_title',
        'slide_subtitle',
        'slide_description',
        'button_name',
        'button_link',
        'slide_image',
    ];
}
