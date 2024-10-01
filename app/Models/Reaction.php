<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'is_liked'
    ];

    protected function casts(): array
    {
        return [
            'is_liked' => 'bool'
        ];
    }
}
