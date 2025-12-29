<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{

    // 投稿一覧
    public function index()
    {
        $posts = Post::with('tags')->latest()->get();
        $tags = Tag::withCount('posts')->get();
        $totalPosts = Post::count();

        return view('posts.index', compact('posts', 'tags', 'totalPosts'));
    }

    // 投稿作成画面
    public function create()
    {
        $tags = Tag::all();
        return view('posts.create', compact('tags'));
    }

    // 投稿保存
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'body'  => 'required|string|max:500',
            'tags'  => 'nullable|array|max:10',
            'tags.*'=> 'exists:tags,id',
        ], [
            'title.required' => 'タイトルは必須です',
            'title.max'      => 'タイトルは50文字以内で入力してください',
            'body.required'  => '本文は必須です',
            'body.max'       => '本文は500文字以内で入力してください',
            'tags.max'       => 'タグは10個までしか登録できません',
            'tags.*.exists'  => '選択されたタグは無効です',
        ]);

        $post = Post::create([
            'title' => $validated['title'],
            'body'  => $validated['body'],
        ]);

        // タグを同期（未指定時は空配列）
        $post->tags()->sync($validated['tags'] ?? []);

        return redirect('/posts');
    }

    // 投稿編集画面
    public function edit(Post $post)
    {
        $tags = Tag::all();
        return view('posts.edit', compact('post', 'tags'));
    }

    // 投稿更新
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'body'  => 'required|string|max:500',
            'tags'  => 'nullable|array|max:10',
            'tags.*'=> 'exists:tags,id',
        ], [
            'title.required' => 'タイトルは必須です',
            'title.max'      => 'タイトルは50文字以内で入力してください',
            'body.required'  => '本文は必須です',
            'body.max'       => '本文は500文字以内で入力してください',
            'tags.max'       => 'タグは10個までしか登録できません',
            'tags.*.exists'  => '選択されたタグは無効です',
        ]);

        $post->update([
            'title' => $validated['title'],
            'body'  => $validated['body'],
        ]);

        // タグの同期
        $post->tags()->sync($validated['tags'] ?? []);

        return redirect('/posts');
    }

    // 投稿削除
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect('/posts');
    }

    // タグ別投稿一覧
    public function postsByTag(Tag $tag)
    {
        $posts = $tag->posts()->with('tags')->latest()->get();
        $tags = Tag::withCount('posts')->get();
        $totalPosts = Post::count();

        return view('posts.index', compact('posts', 'tags', 'tag', 'totalPosts'));
    }

    // 検索機能
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $posts = Post::with('tags')
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                      ->orWhere('body', 'like', "%{$keyword}%");
            })
            ->latest()
            ->get();

        $tags = Tag::withCount('posts')->get();
        $totalPosts = Post::count();

        return view('posts.index', compact('posts', 'tags', 'totalPosts'))
            ->with('keyword', $keyword);
    }
}
