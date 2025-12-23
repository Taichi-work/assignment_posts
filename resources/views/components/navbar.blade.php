<nav class="sticky top-0 z-50 bg-slate-900/80 backdrop-blur-md px-6 py-4 flex justify-between items-center shadow-2xl">
    <div class="text-2xl font-bold text-white">MyPosts</div>
    <div class="flex gap-4">
        <a href="/posts"
           class="px-4 py-2 rounded-2xl transition-all duration-300
           {{ request()->is('posts') ? 'bg-indigo-500' : 'bg-indigo-500/60 hover:bg-indigo-500' }}">
            投稿一覧
        </a>
        <a href="/posts/create"
           class="px-4 py-2 rounded-2xl transition-all duration-300
           {{ request()->is('posts/create') ? 'bg-purple-600' : 'bg-purple-600/60 hover:bg-purple-600' }}">
            新規投稿
        </a>
        <a href="/tags"
           class="px-4 py-2 rounded-2xl transition-all duration-300
           {{ request()->is('tags*') ? 'bg-violet-600' : 'bg-violet-600/60 hover:bg-violet-600' }}">
            タグ管理
        </a>
    </div>
</nav>
