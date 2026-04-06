<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeWebhookController;

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

// Stripe Webhook 受信
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

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

    // 配送先変更画面表示
    Route::get('/purchase/address/{item_id}', [ItemController::class, 'editAddress']);

    // 配送先変更保存
    Route::post('/purchase/address/{item_id}', [ItemController::class, 'updateAddress']);

    // マイページ表示
    Route::get('/mypage', [ProfileController::class, 'mypage']);

    // プロフィール編集画面表示
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);

    // プロフィール更新
    Route::post('/mypage/profile', [ProfileController::class, 'update']);

    // Stripe Checkout へ遷移
    Route::post('/purchase/{item_id}/checkout', [ItemController::class, 'checkout']);

    // Stripe Checkout からの戻り先
    Route::get('/purchase/success/{item_id}', [ItemController::class, 'purchaseSuccess']);
    Route::get('/purchase/cancel/{item_id}', [ItemController::class, 'purchaseCancel']);
});
