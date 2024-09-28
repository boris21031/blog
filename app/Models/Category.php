<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Article;

class Category extends Model {
    protected $table='categories';

    protected $fillable = [
        'title', 'slug',
    ];

    public function article(){
        return $this->hasMany(Article::class);
    }
}
