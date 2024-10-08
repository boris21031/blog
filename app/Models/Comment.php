<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Article;

class Comment extends Model {

    protected $table='comments';

    protected $fillable = [
        'comment', 'author_id', 'article_id',
    ];
    public function author(){
        return $this->belongsTo(User::class);
    }

    public function article(){
        return $this->belongsTo(Article::class);
    }
}
