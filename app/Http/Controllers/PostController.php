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
        $totalPosts = Post::count(); // 総投稿数

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
        $post = Post::create([
            'title' => $request->title,
            'body'  => $request->body,
        ]);

        $post->tags()->sync($request->tags ?? []);

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
        $post->update($request->only('title', 'body'));
        $post->tags()->sync($request->tags ?? []);

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
        $totalPosts = Post::count(); // 総投稿数

        return view('posts.index', compact('posts', 'tags', 'tag', 'totalPosts'));
    }

    // 検索機能
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        // タイトルまたは本文に部分一致する投稿を取得
        $posts = Post::with('tags')
            ->where('title', 'like', "%{$keyword}%")
            ->orWhere('body', 'like', "%{$keyword}%")
            ->latest()
            ->get();

        // タグごとの投稿件数
        $tags = Tag::withCount('posts')->get();

        return view('posts.index', compact('posts', 'tags'))
            ->with('keyword', $keyword);
    }
}
