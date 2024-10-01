<?php

namespace App\Http\Controllers\Api;

use App\Enums\CategoryEnum;
use App\Enums\Post\PostStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryController extends Controller {

    protected array $fillable = [
        'title'
    ];

    protected function casts(): array
    {
        return [
            'category' => CategoryEnum::class
        ];
    }
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function getEnum(): ?CategoryEnum
    {
        return CategoryEnum::tryFrom(array_search($this->title, array_column(CategoryEnum::cases(), 'label')));
    }
}
