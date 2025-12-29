<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;

Route::get('/', function () {
    return redirect('/posts');
});

// 投稿一覧
Route::get('/posts', [PostController::class, 'index']);

// 投稿作成
Route::get('/posts/create', [PostController::class, 'create']);
Route::post('/posts', [PostController::class, 'store']);

// 投稿編集画面（ルート名を追加）
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');

// 投稿更新処理
Route::put('/posts/{post}', [PostController::class, 'update']);

// 投稿削除処理
Route::delete('/posts/{post}', [PostController::class, 'destroy']);

// タグ別投稿一覧
Route::get('/tags/{tag}', [PostController::class, 'postsByTag']);

// **検索用ルートを追加**
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');

// ダッシュボード
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// プロフィール管理
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 認証関連
require __DIR__.'/auth.php';

// タグ管理画面
Route::get('/tags', [TagController::class, 'index'])->name('tags.index');

// タグ作成
Route::post('/tags', [TagController::class, 'store'])->name('tags.store');

// タグ編集画面
Route::get('/tags/{tag}/edit', [TagController::class, 'edit'])->name('tags.edit');

// タグ更新
Route::put('/tags/{tag}', [TagController::class, 'update'])->name('tags.update');

// タグ削除
Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');