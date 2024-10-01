<?php

namespace App\Models;

use App\Enums\ArticleStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Article extends Model {

    protected $table='articles';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'text',
        'status',
        'image',
        'author_id',
        'category_id',
    ];

    protected function casts(): array
    {
        return [
            'status' => ArticleStatusEnum::class
        ];
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    public function reaction(): HasOne
    {
        return $this->reactions()->when(auth()->id(),function($query){
            $query->where('user_id', auth()->id());
        })->one();
    }

    public function scopeFilterByCategory(Builder $query, string|null $categoryId): void
    {
        $query->when($categoryId, function (Builder $query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        });
    }

    public function scopeFilterByTag(Builder $query, string|null $tag): void
    {
        $query->when($tag, function (Builder $query) use ($tag) {
            $query->whereHas('tags', function ($query) use ($tag) {
                $query->where('name', $tag);
            });
        });
    }

    public function scopeSearch(Builder $query, $search): void
    {
        $query->when($search, function (Builder $query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        });
    }

}
