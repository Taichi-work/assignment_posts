<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>投稿一覧</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-white font-sans">

    @include('components.navbar')

    <!-- 検索フォーム -->
    <div class="max-w-6xl mx-auto px-4 py-4">
        <form method="GET" action="{{ url('/posts/search') }}" class="flex gap-2">
            <input type="text" name="keyword" placeholder="キーワードで検索"
                value="{{ $keyword ?? '' }}"
                class="flex-1 px-4 py-2 rounded-2xl bg-slate-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-500 rounded-2xl hover:scale-105 transition-all duration-300">
                検索
            </button>
        </form>

        @isset($keyword)
            <p class="mt-2 text-gray-400">検索結果: 「{{ $keyword }}」</p>
        @endisset
    </div>


    <div class="max-w-6xl mx-auto px-4 py-6 space-y-8">

        <!-- タグ一覧 -->
        <div class="flex flex-wrap gap-3 mb-4">
            <!-- Allタグ：総投稿数を表示 -->
            <a href="/posts"
            class="px-3 py-1 rounded-full bg-indigo-500/30 hover:bg-indigo-500/60 transition-all duration-300">
                All ({{ $totalPosts }})
            </a>

            <!-- 個別タグ -->
            @foreach ($tags as $t)
                <a href="/tags/{{ $t->id }}"
                class="px-3 py-1 rounded-full bg-indigo-500/30 hover:bg-indigo-500/60 transition-all duration-300">
                    {{ $t->name }} ({{ $t->posts_count }})
                </a>
            @endforeach
        </div>


        <!-- 投稿グリッド -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($posts as $post)
                <div class="relative p-6 rounded-2xl backdrop-blur-md bg-white/10 shadow-2xl hover:translate-y-[-5px] transition-transform duration-300">
                    <!-- 投稿タイトル -->
                    <h2 class="text-xl font-semibold mb-2">{{ $post->title }}</h2>
                    <!-- 投稿本文 -->
                    <p class="mb-3 line-clamp-3">{{ $post->body }}</p>
                    <!-- タグ -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach ($post->tags as $tag)
                            <a href="/tags/{{ $tag->id }}" class="text-sm px-2 py-1 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:scale-105 transition-all duration-300">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                    <!-- アクションボタン -->
                    <div class="flex gap-2" x-data="{ open: false }">
                        <!-- 編集ボタン -->
                        <a href="{{ route('posts.edit', ['post' => $post->id, 'return' => url()->full()]) }}"
                        class="px-3 py-1 bg-indigo-500 rounded-2xl hover:scale-110 active:scale-95 transition-all duration-300">
                            編集
                        </a>

                        <!-- 削除ボタン -->
                        <button @click="open = true" type="button"
                            class="px-3 py-1 bg-red-500 rounded-2xl hover:scale-110 active:scale-95 transition-all duration-300">
                            削除
                        </button>

                        <!-- モーダル（初期状態は非表示） -->
                        <div x-show="open" x-transition.opacity
                            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
                            style="display: none;">
                            <div class="bg-slate-950 rounded-2xl p-6 w-96 shadow-2xl text-white">
                                <p class="mb-6 text-lg font-semibold">本当に削除してもよいですか？</p>
                                <div class="flex justify-end gap-4">
                                    <!-- キャンセル -->
                                    <button @click="open = false" type="button"
                                            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-2xl transition-all">
                                        キャンセル
                                    </button>

                                    <!-- 削除フォーム -->
                                    <form method="POST" action="/posts/{{ $post->id }}">
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
                </div>
            @empty
                <p class="text-gray-400 col-span-full">投稿はまだありません。</p>
            @endforelse
        </div>

    </div>

    <!-- Floating Action Button -->
    <a href="/posts/create" class="fixed bottom-8 right-8 w-16 h-16 bg-indigo-500 rounded-full flex items-center justify-center text-white text-2xl shadow-2xl ring-4 ring-offset-2 hover:scale-110 transition-all duration-300 animate-pulse">
        +
    </a>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</body>
</html>
