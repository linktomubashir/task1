<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'subject', 'email', 'message','reply_message', 'status'];
}
