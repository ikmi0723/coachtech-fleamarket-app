<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * 商品一覧画面を表示
     */
    public function index()
    {
        $items = Item::latest()->get();

        return view('items.index', compact('items'));
    }

    /**
     * 商品詳細画面を表示
     */
    public function show($item_id)
    {
        // 商品と一緒にカテゴリ情報も取得する
        $item = Item::with('categories')->findOrFail($item_id);

        return view('items.show', compact('item'));
    }
}
