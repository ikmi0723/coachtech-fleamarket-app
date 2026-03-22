<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 商品一覧画面
Route::get('/', [ItemController::class, 'index']);

Route::middleware(['auth'])->group(function () {
    Route::get('/mypage/profile', function () {
        return view('mypage.profile');
    });
});

// 商品詳細画面
Route::get('/item/{item_id}', [ItemController::class, 'show']);

// いいね登録・解除
Route::middleware('auth')->group(function () {
    Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike']);

    // コメント送信
    Route::post('/item/{item_id}/comment', [ItemController::class, 'storeComment']);
});
