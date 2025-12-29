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
                class="px-4 py-2 bg-indigo-500 rounded-2xl hover:scale-105 transition-all">
            検索
        </button>
    </form>

    @isset($keyword)
        <p class="mt-2 text-gray-400">検索結果: 「{{ $keyword }}」</p>
    @endisset
</div>

<div class="max-w-6xl mx-auto px-4 py-6 space-y-8"
    x-data="{
        showPostModal: false,
        modalTitle: '',
        modalBody: '',
        modalCreatedAt: '',
        modalTags: [],
        openPost(el) {
            this.modalTitle = el.dataset.title
            this.modalBody  = el.dataset.body
            this.modalTags  = JSON.parse(el.dataset.tags)
            this.modalCreatedAt = el.dataset.createdAt
            this.showPostModal = true
        }
    }">

    <!-- タグ一覧 -->
    <div class="flex flex-wrap gap-3 mb-4">
        <a href="/posts"
           class="px-3 py-1 rounded-full bg-indigo-500/30 hover:bg-indigo-500/60">
            All ({{ $totalPosts }})
        </a>

        @foreach ($tags as $t)
            <a href="/tags/{{ $t->id }}"
               class="px-3 py-1 rounded-full bg-indigo-500/30 hover:bg-indigo-500/60">
                {{ $t->name }} ({{ $t->posts_count }})
            </a>
        @endforeach
    </div>

    <!-- 投稿グリッド -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($posts as $post)
            <div class="relative p-6 rounded-2xl bg-white/10 shadow-2xl">

                <h2
                    class="text-xl font-semibold mb-2 cursor-pointer text-indigo-400 hover:underline break-words"
                    data-title="{{ $post->title }}"
                    data-body="{{ $post->body }}"
                    data-tags='@json($post->tags->pluck("name"))'
                    data-created-at="{{ $post->created_at->format('Y/m/d H:i') }}"
                    @click="openPost($el)"
                    >
                    {{ $post->title }}
                </h2>

                <p class="text-xs text-gray-400 mb-2">
                    {{ $post->created_at->format('Y/m/d H:i') }}
                </p>

                <p
                    class="mb-3 line-clamp-3 text-gray-200 cursor-pointer break-words"
                    @click="openPost($el.previousElementSibling)"
                    >
                    {{ $post->body }}
                </p>

                <!-- タグ -->
                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach ($post->tags as $tag)
                        <a href="/tags/{{ $tag->id }}"
                           class="text-sm px-2 py-1 rounded-full bg-indigo-500/40">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>

                <!-- 編集・削除（そのまま） -->
                <div class="flex gap-2" x-data="{ openDelete: false }">
                    <a href="{{ route('posts.edit', ['post' => $post->id, 'return' => url()->full()]) }}"
                       class="px-3 py-1 bg-indigo-500 rounded-2xl">
                        編集
                    </a>

                    <button @click="openDelete = true"
                            class="px-3 py-1 bg-red-500 rounded-2xl">
                        削除
                    </button>

                    <!-- 削除モーダル -->
                    <div x-show="openDelete" x-transition.opacity
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
                         style="display: none;">
                        <div class="bg-slate-950 p-6 rounded-2xl w-96">
                            <p class="mb-6 text-lg font-semibold">本当に削除してもよいですか？</p>
                            <div class="flex justify-end gap-4">
                                <button @click="openDelete = false"
                                        class="px-4 py-2 bg-gray-700 rounded-2xl">
                                    キャンセル
                                </button>
                                <form method="POST" action="/posts/{{ $post->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-4 py-2 bg-red-600 rounded-2xl">
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

    <!-- ★ 投稿全文モーダル -->
    <div x-show="showPostModal"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
        style="display: none;"
        @click.self="showPostModal = false">

    <div class="bg-slate-950 max-w-2xl w-full p-6 rounded-2xl shadow-2xl relative max-h-[80vh] overflow-y-auto">

        <!-- 閉じるボタン -->
        <button @click="showPostModal = false" class="absolute top-3 right-3 text-gray-400 text-2xl hover:text-white">×</button>

        <!-- タイトル -->
        <h2 class="text-2xl font-bold mb-4 break-words" x-text="modalTitle"></h2>

        <!-- 日時 -->
        <p class="text-xs text-gray-400 mb-2" x-text="modalCreatedAt"></p>

        <!-- タグ -->
        <div class="flex flex-wrap gap-2 mb-4">
            <template x-for="tag in modalTags" :key="tag">
                <span class="text-sm px-2 py-1 rounded-full bg-indigo-500/40 break-words">
                    #<span x-text="tag"></span>
                </span>
            </template>
        </div>

        <!-- 本文 -->
        <p class="text-gray-200 whitespace-pre-wrap break-words"><span x-text="modalBody"></span></p>
    </div>
</div>

<!-- FAB -->
<a href="/posts/create"
   class="fixed bottom-8 right-8 w-16 h-16 bg-indigo-500 rounded-full flex items-center justify-center text-2xl shadow-2xl">
    +
</a>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
