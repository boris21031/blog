<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'image'=>asset('images/'.$this->image),
            'title'=>$this->title,
            'description'=>$this->description,
            'slug'=>$this->slug,
            'text'=>$this->text,
            'category_title'=>$this->category->title,
            'category'=>$this->category_id,
            'author'=>$this->author->name,
//            'comments'=> $this->comment->comments->count(),
        ];
    }
}
