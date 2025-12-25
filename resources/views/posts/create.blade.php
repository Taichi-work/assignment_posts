<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>投稿作成</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-white font-sans">

<div class="max-w-3xl mx-auto p-6">

    <!-- ページタイトル -->
    <h1 class="text-3xl font-bold mb-6">投稿作成</h1>

    <!-- 戻るボタン -->
    <a href="/posts"
       class="inline-block mb-6 px-4 py-2 bg-gray-800 rounded-2xl hover:bg-gray-700 transition-all duration-300">
        投稿一覧に戻る
    </a>

    <!-- 投稿フォーム -->
    <form method="POST" action="/posts" class="space-y-6 p-6 backdrop-blur-md bg-white/10 rounded-2xl shadow-2xl">
        @csrf

        <!-- タイトル -->
        <div class="flex flex-col">
            <label class="mb-2 font-semibold">タイトル</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   class="p-3 rounded-xl bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            @error('title')
                <p class="text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- 本文 -->
        <div class="flex flex-col">
            <label class="mb-2 font-semibold">本文</label>
            <textarea name="body" rows="5"
                      class="p-3 rounded-xl bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('body') }}</textarea>
            @error('body')
                <p class="text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- タグ選択 -->
        <div class="flex flex-col">
            <label class="mb-2 font-semibold">タグ</label>
            <div class="flex flex-wrap gap-3">
                @foreach ($tags as $tag)
                    <label class="flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/30 hover:bg-indigo-500/50 cursor-pointer transition-all duration-300">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                               class="accent-indigo-400"
                               {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                        <span>{{ $tag->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('tags')
                <p class="text-red-500 mt-1">{{ $message }}</p>
            @enderror
            @error('tags.*')
                <p class="text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- 送信ボタン -->
        <button type="submit"
                class="w-full py-3 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl font-bold text-white text-lg hover:scale-105 active:scale-95 transition-all duration-300">
            投稿する
        </button>
    </form>

</div>

</body>
</html>
