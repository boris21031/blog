<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReactionRequest;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class ReactionController extends Controller
{
    public function store(StoreReactionRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            Reaction::query()->updateOrCreate(
                ['user_id' => $request->author_id, 'article_id' => $request->article_id],
                ['is_liked' => $request->is_liked]
            );
            return Response::json(['success'=>'Comment added successfully !']);
        } catch (\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'еще не придумал формат вывода ошибки']);
        }
    }

    public function destroy(StoreReactionRequest $requestReaction): \Illuminate\Http\JsonResponse
    {
        try{
            $reaction=Reaction::where('article_id',$requestReaction->article_id)->where('user_id', Auth::user()->id)->first();
            if($reaction){
                $reaction->delete();
                return Response::json(['success'=>'Comment removed successfully !']);
            }else{
                return Response::json(['error'=>'Comment not found!']);
            }
        }catch(\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'Comment belongs to author/article.So you cann\'t delete this comment!']);
        }
    }
}
