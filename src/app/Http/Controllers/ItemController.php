<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Category;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

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
        $item = Item::with(['categories', 'likes', 'comments.user', 'purchase'])->findOrFail($item_id);

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
        $item = Item::with('purchase')->findOrFail($item_id);

        // 購入済み商品の再購入を防ぐ
        if ($item->purchase) {
            return redirect('/item/' . $item_id)
                ->with('error', 'この商品はすでに購入されています。');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // セッションに配送先変更情報があればそれを優先、なければプロフィール住所を使う
        $shippingAddress = session('purchase_address_' . $item->id, [
            'postcode' => $user->postcode,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        return view('items.purchase', compact('item', 'shippingAddress'));
    }

    /**
     * 購入保存
     * ※ Stripe導入後は基本使わない想定だが、残しておく場合は二重購入防止を入れる
     */
    public function storePurchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::with('purchase')->findOrFail($item_id);

        // すでに購入済みなら処理しない
        if ($item->purchase) {
            return redirect('/item/' . $item_id)
                ->with('error', 'この商品はすでに購入されています。');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $shippingAddress = session('purchase_address_' . $item->id, [
            'postcode' => $user->postcode,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => $request->input('payment_method'),
            'postcode' => $shippingAddress['postcode'],
            'address' => $shippingAddress['address'],
            'building' => $shippingAddress['building'],
        ]);


        // 購入完了後は一時保存した配送先情報を削除
        session()->forget('purchase_address_' . $item->id);

        return redirect('/');
    }

    /**
     * 配送先変更画面を表示
     */
    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $address = session('purchase_address_' . $item->id, [
            'postcode' => $user->postcode,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        return view('items.address', compact('item', 'address'));
    }

    /**
     * 配送先変更内容を一時保存する
     */
    public function updateAddress(AddressRequest $request, $item_id)
    {
        Item::findOrFail($item_id);

        session([
            'purchase_address_' . $item_id => [
                'postcode' => $request->input('postcode'),
                'address' => $request->input('address'),
                'building' => $request->input('building'),
            ],
        ]);

        return redirect('/purchase/' . $item_id);
    }

    /**
     * Stripe Checkout セッションを作成して決済画面へリダイレクトする
     */
    public function checkout(PurchaseRequest $request, $item_id)
    {
        $item = Item::with('purchase')->findOrFail($item_id);

        // すでに購入済みなら処理しない
        if ($item->purchase) {
            return redirect('/item/' . $item_id)
                ->with('error', 'この商品はすでに購入されています。');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $shippingAddress = session('purchase_address_' . $item->id, [
            'postcode' => $user->postcode,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        // 支払い方法の選択値に応じて Stripe の payment_method_types を分ける
        $paymentMethodTypes = $request->input('payment_method') === 'convenience'
            ? ['konbini']
            : ['card'];

        $session = StripeSession::create([
            'mode' => 'payment',

            // カード or コンビニ
            'payment_method_types' => $paymentMethodTypes,

            // Konbini ではメールが必要になるため渡しておく
            'customer_email' => $user->email,

            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    // JPY はそのまま整数で渡す
                    'unit_amount' => (int) $item->price,
                ],
                'quantity' => 1,
            ]],

            // success 側で参照しやすいように metadata を入れる
            'metadata' => [
                'item_id' => (string) $item->id,
                'user_id' => (string) $user->id,
                'postcode' => $shippingAddress['postcode'] ?? '',
                'address' => $shippingAddress['address'] ?? '',
                'building' => $shippingAddress['building'] ?? '',
                'payment_method' => $request->input('payment_method'),
            ],

            'success_url' => url('/purchase/success/' . $item->id) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => url('/purchase/cancel/' . $item->id),
        ]);

        return redirect($session->url);
    }

    /**
     * Stripe Checkout 成功後の戻り先
     * カード決済はここで購入保存
     *
     * ※ コンビニ決済は本来 webhook で確定するのが安全
     */
    public function purchaseSuccess(Request $request, $item_id)
    {
        $item = Item::with('purchase')->findOrFail($item_id);

        // すでに購入済みならそのまま詳細へ
        if ($item->purchase) {
            return redirect('/item/' . $item_id);
        }

        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect('/purchase/' . $item_id)
                ->with('error', '決済情報が確認できませんでした。');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::retrieve($sessionId);

        // card は paid のとき購入確定
        // convenience は非同期なので、ここでは未完了の可能性あり
        if ($session->payment_status !== 'paid') {
            return redirect('/purchase/' . $item_id)
                ->with('error', 'まだ決済が完了していません。');
        }

        Purchase::create([
            'user_id' => $session->metadata->user_id,
            'item_id' => $session->metadata->item_id,
            'payment_method' => $session->metadata->payment_method ?? 'card',
            'postcode' => $session->metadata->postcode ?? '',
            'address' => $session->metadata->address ?? '',
            'building' => $session->metadata->building ?? '',
        ]);

        session()->forget('purchase_address_' . $item->id);

        return redirect('/item/' . $item_id)
            ->with('message', '購入が完了しました。');
    }

    /**
     * Stripe Checkout キャンセル時の戻り先
     */
    public function purchaseCancel($item_id)
    {
        return redirect('/purchase/' . $item_id)
            ->with('error', '決済をキャンセルしました。');
    }
}
