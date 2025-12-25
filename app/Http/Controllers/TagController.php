<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // タグ一覧画面
    public function index()
    {
        $tags = Tag::withCount('posts')->get();
        return view('tags.index', compact('tags'));
    }

    // 新規タグ作成
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:20|unique:tags,name',
        ], [
            'name.required' => 'タグ名は必須です',
            'name.max' => 'タグ名は20文字までです',
            'name.unique' => 'このタグは既に存在します',
        ]);

        Tag::create($validated);

        return redirect()->route('tags.index');
    }

    // タグ更新
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:20|unique:tags,name,' . $tag->id,
        ], [
            'name.required' => 'タグ名は必須です',
            'name.max' => 'タグ名は20文字までです',
            'name.unique' => 'このタグは既に存在します',
        ]);

        $tag->update($validated);

        return redirect()->route('tags.index');
    }

    // タグ削除
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('tags.index');
    }
}
