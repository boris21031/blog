<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReactionRequest;
use App\Models\Reaction;
use Illuminate\Http\RedirectResponse;

class ReactionController extends Controller
{
    public function store(StoreReactionRequest $request): RedirectResponse
    {
        Reaction::query()->updateOrCreate(
            ['user_id' => $request->user_id, 'post_id' => $request->post_id],
            ['is_liked' => $request->is_liked]
        );
        return back();
    }

    public function destroy(Reaction $reaction): RedirectResponse
    {
        $reaction->delete();
        return back();
    }
}
