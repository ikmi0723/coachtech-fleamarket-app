<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * 商品一覧画面を表示
     */
    public function index(Request $request)
    {
        // 検索キーワードを取得
        $keyword = $request->input('keyword');

        // 表示するタブを取得（初期値は recommend）
        $tab = $request->input('tab', 'recommend');

        // おすすめタブ
        if ($tab === 'recommend') {
            // 商品一覧のベースクエリ
            $query = Item::query();

            // ログイン中は自分が出品した商品を一覧から除外する
            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }

            // キーワードが入力されている場合は商品名で部分一致検索
            if (!empty($keyword)) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }

            $items = $query->with('purchase')->latest()->get();
        } else {
            // マイリストタブ
            if (Auth::check()) {
                $query = Item::query()
                    ->whereHas('likes', function ($query) {
                        $query->where('user_id', Auth::id());
                    });

                // キーワードが入力されている場合は商品名で部分一致検索
                if (!empty($keyword)) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                }

                $items = $query->with('purchase')->latest()->get();
            } else {
                // 未ログイン時のマイリストは何も表示しない
                $items = collect();
            }
        }

        return view('items.index', compact('items', 'tab'));
    }

    /**
     * 商品詳細画面を表示
     */
    public function show($item_id)
    {
        // 商品と一緒にカテゴリ情報・いいね情報も取得する
        $item = Item::with(['categories', 'likes'])->findOrFail($item_id);

        // ログイン中ユーザーがこの商品にいいね済みかどうかを判定
        $isLiked = false;

        if (Auth::check()) {
            $isLiked = $item->likes()->where('user_id', Auth::id())->exists();
        }

        return view('items.show', compact('item', 'isLiked'));
    }

    /**
     * いいね登録 / 解除
     */
    public function toggleLike($item_id)
    {
        $item = Item::findOrFail($item_id);

        $like = Like::where('user_id', Auth::id())
            ->where('item_id', $item->id)
            ->first();

        // すでにいいね済みなら削除、未いいねなら登録
        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => Auth::id(),
                'item_id' => $item->id,
            ]);
        }

        return redirect()->back();
    }
}
