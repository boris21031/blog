<?php

namespace App\Http\Controllers\Api;

use App\Enums\ArticleStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Rules\CategoryExists;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    private array $categories = [
        'designer' => 1,
        'freelancer' => 2,
        'tutor' => 3,
        'marketer' => 4,
        'programmer' => 5,
        'production' => 6,
        'photographer' => 7,
    ];

    // show all the article
    public function index(Request $request)
    {
        $query = Article::query();
        // Filter by status PUBLISHED
        $query->where('status', ArticleStatusEnum::PUBLISHED);

        // Фильтрация по категории
        if ($request->has('category')) {
            $categoryName = $request->get('category');
            if (array_key_exists($categoryName, $this->categories)) {
                $categoryId = $this->categories[$categoryName];
                $query->filterByCategory($categoryId);
            } else {
                // Обработка случая, когда категория не найдена
                // Например, можно выбросить исключение или установить ошибку
            }
        }

        // Поиск по заголовку
        if ($request->has('search')) {
            $query->search($request->get('search'));
        }

        // Сортировка по свежести
        if ($request->get('sort') === 'latest') {
            $query->orderBy('created_at', 'desc');
        }
        $query->withCount('reactions');
        $query->with('comment.author');

        $articles = $query->paginate(10);

        return response()->json(ArticleResource::collection($articles));
    }

    // store new article into the database
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'text' => 'required|string',
            'status' => 'required|integer|in:1,2,3',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category' => ['required', 'string', new CategoryExists],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $category = Category::where('title', $request->category)->first();
        $data = $request->only(['title', 'description', 'text','body', 'status']);
        $data['slug'] = Str::slug($request->title);
        $data['author_id'] = auth()->id();
        $data['category_id'] = $category->id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $filename);
            $data['image'] = $filename;
        }
        $article = Article::create($data);

        return Response::json([
            'article' => new ArticleResource($article),
            'success' => 'ArticleStoreRequest created successfully !'
        ]);
    }

    // show a specific article by id
    public function show($id)
    {
        if (Article::where('id', $id)->first()) {
            return new ArticleResource(Article::findOrFail($id));
        } else {
            return Response::json(['error' => 'ArticleStoreRequest not found!']);
        }
    }

    // update article using id
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'text' => 'required|string',
            'status' => 'required|integer|in:1,2,3',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category' => ['required', 'string', new CategoryExists],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $article = Article::findOrFail($id);
        $category = Category::where('title', $request->category)->first();

        $data = $request->only(['title', 'description', 'text', 'status']);
        $data['slug'] = Str::slug($request->title);
        $data['category_id'] = $category->id;

        if ($request->hasFile('image')) {
            // Remove old image if exists
            if ($article->image) {
                $oldImagePath = public_path('images') . '/' . $article->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $filename);
            $data['image'] = $filename;
        }

        $article->update($data);

        return Response::json([
            'article' => new ArticleResource($article),
            'success' => 'Article updated successfully!'
        ]);
    }

    // remove article using id
    public function remove(Request $request)
    {
        try {
            $article = Article::where('id', $request->id)->where('author_id', Auth::user()->id)->first();
            if ($article) {
                $article->delete();
                return Response::json(['success' => 'ArticleStoreRequest removed successfully !']);
            } else {
                return Response::json(['error' => 'ArticleStoreRequest not found!']);
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            return Response::json(['error' => 'ArticleStoreRequest belongs to comment.So you cann\'t delete this article!']);
        }
    }

    // search article by keyword
    public function searchArticle(Request $request)
    {
        $articles = Article::where('title', 'LIKE', '%' . $request->keyword . '%')->get();
        if (count($articles) == 0) {
            return Response::json(['message' => 'No article match found !']);
        } else {
            return Response::json($articles);
        }
    }

    // fetch comments for a specific article
    public function comments($id)
    {
        if (Article::where('id', $id)->first()) {
            return CommentResource::collection(Comment::where('article_id', $id)->get());
        } else {
            return Response::json(['error' => 'ArticleStoreRequest not found!']);
        }
    }
}
