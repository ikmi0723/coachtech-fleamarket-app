<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use App\Models\Category;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use App\Models\Purchase;

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
        // 商品と一緒にカテゴリ情報・いいね情報・コメント情報も取得する
        $item = Item::with(['categories', 'likes', 'comments.user'])->findOrFail($item_id);

        // ログイン中ユーザーがこの商品にいいね済みかどうかを判定
        $isLiked = false;

        if (Auth::check()) {
            $isLiked = $item->likes()->where('user_id', Auth::id())->exists();
        }

        return view('items.show', compact('item', 'isLiked'));
    }

    /**
     * 出品画面を表示
     */
    public function create()
    {
        $categories = Category::all();

        return view('items.create', compact('categories'));
    }

    /**
     * 出品保存
     */
    public function store(ExhibitionRequest $request)
    {
        // 画像を storage/app/public/items に保存
        $imagePath = $request->file('image')->store('items', 'public');

        // 商品を保存
        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
            'brand' => $request->input('brand'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'condition' => $request->input('condition'),
            'image_path' => $imagePath,
        ]);

        // 選択されたカテゴリを中間テーブルに保存
        $item->categories()->attach($request->input('categories'));

        // 保存後は商品一覧へリダイレクト
        return redirect('/');
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

    /**
     * コメント送信
     */
    public function storeComment(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $item->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        return redirect()->back();
    }

    /**
     * 購入画面を表示
     */
    public function purchase($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('items.purchase', compact('item'));
    }

    /**
     * 購入保存
     */
    public function storePurchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        Purchase::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'payment_method' => $request->input('payment_method'),
            // 今は仮で固定値、後でプロフィール情報や住所変更と連動させる
            'postcode' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        return redirect('/');
    }
}
