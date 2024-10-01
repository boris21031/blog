<?php

namespace App\Http\Requests\Article;


use App\Enums\ArticleStatusEnum;
use App\Models\Article;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ArticleUpdateRequest extends ArticleCommonRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return parent::rules() + [
                'status' => [
                    'int',
                    new Enum(ArticleStatusEnum::class)
                ],
                'slug' => ['string', Rule::unique(Article::class, 'slug')->ignore($this->article, 'slug')],
            ];
    }
}
