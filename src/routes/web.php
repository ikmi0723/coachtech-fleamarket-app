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

// 商品詳細画面
Route::get('/item/{item_id}', [ItemController::class, 'show']);

// 認証必須の機能
Route::middleware('auth')->group(function () {
    // いいね登録・解除
    Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike']);

    // コメント送信
    Route::post('/item/{item_id}/comment', [ItemController::class, 'storeComment']);

    // 出品画面表示
    Route::get('/sell', [ItemController::class, 'create']);

    // 出品保存
    Route::post('/sell', [ItemController::class, 'store']);

    // 購入画面表示
    Route::get('/purchase/{item_id}', [ItemController::class, 'purchase']);

    // 購入保存
    Route::post('/purchase/{item_id}', [ItemController::class, 'storePurchase']);
});
