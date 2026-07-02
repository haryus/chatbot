<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = ['client_id', 'agent_id', 'status'];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function messages()
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id');
    }
}
