<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// 公開トップ画面（お問い合わせフォーム入力ページ / PG01）
Route::get('/', [ContactController::class, 'index'])->name('contact.index');

// 認証が必要なルート（管理画面 / PG05）
Route::middleware('auth')->group(function () {
    Route::get('/admin', function () {
        return Blade::render('
            管理画面（準備中）<br>
            <form method="POST" action="{{ route(\'logout\') }}">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
        ');
    })->name('admin.index');
});