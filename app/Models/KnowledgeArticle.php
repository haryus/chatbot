<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeArticle extends Model
{
    protected $fillable = [
        'title',
        'project',
        'content',
    ];
}
