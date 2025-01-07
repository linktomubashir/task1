<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'action',
        'model_type',
        'model_id',
        'user_id',
        'changes',
        'performed_at',
    ];

    protected $casts = [ 'changes' => 'array', ];

    public function model()
    {
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    // public function getFormattedChangesAttribute()
    // {
    //     $changes = is_array($this->changes) ? $this->changes : json_decode($this->changes, true);

    //     $formattedChanges = '';

    //     foreach ($changes as $key => $value) {
    //         // Capitalize the first letter of each word in the key and format
    //         $formattedKey = ucwords(str_replace('_', ' ', $key));  // Replace underscores with spaces and capitalize
    //         $formattedChanges .= "<strong>{$formattedKey}:</strong> " . ($value ? $value : 'N/A') . "<br>";
    //     }

    //     return $formattedChanges;
    // }
}
