<?php

namespace App\Http\Requests\Article;

use App\Http\Requests\Common\CommonRequest;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Validation\Rule;


class ArticleCommonRequest extends CommonRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'min:3',
                'max:50',
            ],
            'description' => [
                'required',
                'min:10',
                'max:250',
            ],
            'text' => [
                'required',
                'min:10',
                'max:5000',
            ],
            'category_id' => [
                'int',
                Rule::exists(Category::class, 'id')
            ],
            'tags' => ['array'],
            'tags.*' => [ 'int', Rule::exists(Tag::class, 'id')
            ],
            'image' => [
                'file',
                'extensions:jpg,jpeg,png,webp',
            ]

        ];
    }
    public function messages(): array {
        return [
            'title.required' => 'You missed your fucking title, bitch'
        ];
    }
}
