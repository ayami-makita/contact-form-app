<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

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

// 公開トップ画面（お問い合わせフォーム）
Route::get('/', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contacts/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');
Route::post('/contacts', [ContactController::class, 'store'])->name('contact.store');
Route::get('/thanks', [ContactController::class, 'thanks'])->name('contact.thanks');


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