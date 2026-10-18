<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;

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


// 管理画面ルート（認証必須）
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // 一覧・検索
    Route::get('/', [AdminController::class, 'index'])->name('index');
    // 詳細表示（要件: GET /admin/contacts/{contact}）
    Route::get('/contacts/{contact}', [AdminController::class, 'show'])->name('show');
    // 削除
    Route::delete('/contacts/{contact}', [AdminController::class, 'destroy'])->name('destroy');
});