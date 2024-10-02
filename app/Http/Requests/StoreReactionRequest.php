<?php

namespace App\Http\Requests;

use App\Http\Requests\Common\CommonRequest;
use App\Models\Article;
use Illuminate\Validation\Rule;

class StoreReactionRequest extends CommonRequest
{
    public function rules(): array
    {
        return [
            'is_liked' => [
                'required',
                'bool',
            ],
            'article_id' => [
                'required',
                'int',
                Rule::exists(Article::class, 'id')
            ],
            'user_id' => [
                'required',
                'int',
            ],
        ];
    }
    protected function prepareForValidation(): void {
        $this->merge([
            'is_liked' => $this->boolean('is_liked'),
            'user_id' => auth()->id(),
        ]);
    }
}
