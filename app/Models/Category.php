<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Article;

class Category extends Model {
    protected $table='categories';

    protected $fillable = [
        'title'
    ];

    public array $categories = [
        'designer' => 1,
        'freelancer' => 2,
        'tutor' => 3,
        'marketer' => 4,
        'programmer' => 5,
        'production' => 6,
        'photographer' => 7,
    ];

    public function articles(){
        return $this->hasMany(Article::class);
    }
}
