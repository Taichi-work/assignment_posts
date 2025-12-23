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

    <div class="max-w-4xl mx-auto p-6 bg-slate-950 rounded-2xl shadow-2xl text-white">
        <h1 class="text-2xl font-bold mb-4">タグ管理</h1>

        <!-- 新規タグ追加フォーム -->
        <form method="POST" action="{{ route('tags.store') }}" class="flex gap-2 mb-6">
            @csrf
            <input type="text" name="name" placeholder="新しいタグ名"
                class="flex-1 px-4 py-2 rounded-2xl bg-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-500 rounded-2xl hover:scale-105 transition-all duration-300">
                追加
            </button>
        </form>

        <!-- タグ一覧 -->
        <ul class="space-y-2">
            @foreach ($tags as $tag)
                <li class="flex justify-between items-center p-3 bg-white/10 rounded-2xl shadow-2xl">
                    <span>#{{ $tag->name }}</span>
                    <div class="flex gap-2">
                        <a href="{{ route('tags.edit', $tag->id) }}"
                        class="px-3 py-1 bg-indigo-500 rounded-2xl hover:scale-105 transition-all duration-300">
                            編集
                        </a>
                        <form method="POST" action="{{ route('tags.destroy', $tag->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1 bg-red-500 rounded-2xl hover:scale-105 transition-all duration-300">
                                削除
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</body>
</html>
