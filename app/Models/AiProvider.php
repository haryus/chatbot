<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiProvider extends Model
{
    protected $fillable = ['name', 'provider', 'api_key', 'model', 'base_url', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
