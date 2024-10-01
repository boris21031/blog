<?php

namespace App\Http\Requests\Article;

use App\Models\Article;
use Illuminate\Validation\Rule;

class ArticleStoreRequest extends ArticleCommonRequest
{
    public function rules(): array
    {
        return parent::rules() + [
                'user_id' => ['int'],
                'slug' => ['string', Rule::unique(Article::class, 'slug')],
            ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id(),
            'slug' => str($this->title)->slug()->toString(),
        ]);
    }
}
