<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\History;
use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'subject', 'email', 'message','reply_message', 'status'];

    public function history()
    {
        return $this->morphMany(History::class, 'model');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
