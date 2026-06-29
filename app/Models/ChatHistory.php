<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatHistory extends Model
{
    public $guarded = [];
    
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
