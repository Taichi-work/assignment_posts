<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>タグ編集</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-slate-950 text-white font-sans">

    @include('components.navbar')

    <div class="max-w-3xl mx-auto p-6 space-y-6">

        <!-- ページタイトル -->
        <h1 class="text-3xl font-bold">タグ編集</h1>

        <!-- 戻るボタン -->
        <a href="{{ route('tags.index') }}"
           class="inline-block mb-6 px-4 py-2 bg-gray-800 rounded-2xl hover:bg-gray-700 transition-all duration-300">
            タグ一覧に戻る
        </a>

        <!-- 編集フォーム -->
        <div x-data="{ name: '{{ old('name', $tag->name) }}' }" class="space-y-2">
            <form method="POST" action="{{ route('tags.update', $tag->id) }}" class="space-y-4 p-6 backdrop-blur-md bg-white/10 rounded-2xl shadow-2xl">
                @csrf
                @method('PUT')

                <div class="flex flex-col">
                    <label class="mb-2 font-semibold">タグ名</label>
                    <input type="text" name="name" x-model="name" maxlength="20"
                           class="p-3 rounded-xl bg-white/20 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500
                                  @error('name') border-red-500 @enderror"
                           placeholder="タグ名（最大20文字）">
                </div>

                <!-- 文字数カウント＆警告 -->
                <p class="text-sm" :class="name.length > 20 ? 'text-red-500' : 'text-gray-400'">
                    文字数: <span x-text="name.length"></span>/20
                </p>

                <!-- サーバー側バリデーションエラー -->
                @error('name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror

                <!-- 送信ボタン -->
                <button type="submit"
                        class="w-full py-3 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl font-bold text-white text-lg hover:scale-105 active:scale-95 transition-all duration-300">
                    更新
                </button>
            </form>
        </div>

    </div>

</body>
</html>
