<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    public $guarded = [];
    public function messages()
    {
        return $this->hasMany(ChatHistory::class);
    }
}
