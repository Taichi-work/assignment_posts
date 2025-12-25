<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>タグ管理</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-slate-950 text-white font-sans">

@include('components.navbar')

<div class="max-w-4xl mx-auto p-6 space-y-6">

    <h1 class="text-3xl font-bold">タグ管理</h1>

    <!-- 新規タグ作成フォーム -->
    <div x-data="{ name: '{{ old('name') }}' }" class="space-y-2">
        <form method="POST" action="{{ route('tags.store') }}" class="flex gap-2">
            @csrf
            <input type="text" name="name" x-model="name" maxlength="20"
                   class="flex-1 px-4 py-2 rounded-2xl bg-white/10 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500
                          @error('name') border-red-500 @enderror"
                   placeholder="新しいタグ名（最大20文字）">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-500 rounded-2xl hover:scale-105 transition-all duration-300">
                追加
            </button>
        </form>

        <p class="text-sm" :class="name.length > 20 ? 'text-red-500' : 'text-gray-400'">
            文字数: <span x-text="name.length"></span>/20
        </p>

        @error('name')
        <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <!-- 既存タグ一覧 -->
    <div class="flex flex-wrap gap-3">
        @foreach ($tags as $tag)
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:scale-105 transition-all duration-300"
                 x-data="{ open: false }">
                <span class="font-semibold">#{{ $tag->name }} ({{ $tag->posts_count }})</span>

                <!-- 編集ボタン -->
                <a href="{{ route('tags.edit', $tag->id) }}"
                   class="px-2 py-1 bg-indigo-500 rounded-2xl hover:scale-105 transition-all duration-300">
                    編集
                </a>

                <!-- 削除ボタン -->
                <button type="button" @click="open = true"
                        class="px-2 py-1 bg-red-500 rounded-2xl hover:scale-105 transition-all duration-300">
                    削除
                </button>

                <!-- 削除モーダル（画面中央固定） -->
                <div x-show="open" x-transition.opacity
                     class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
                     style="display: none;">
                    <div class="bg-slate-950 rounded-2xl p-6 w-96 shadow-2xl text-white">
                        <p class="mb-6 text-lg font-semibold">本当に削除してもよいですか？</p>
                        <div class="flex justify-end gap-4">
                            <button @click="open = false" type="button"
                                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-2xl transition-all">
                                キャンセル
                            </button>
                            <form method="POST" action="{{ route('tags.destroy', $tag->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-2xl transition-all">
                                    削除
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

</div>

</body>
</html>
