<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * マイページを表示
     */
    public function mypage()
    {
        /** @var User $user */
        $user = Auth::user();

        // 表示するタブを取得（初期値は sell）
        $tab = request('tab', 'sell');

        // ログインユーザーの出品商品一覧
        $sellItems = Item::where('user_id', $user->id)
            ->latest()
            ->get();

        // ログインユーザーの購入商品一覧
        $buyItems = Item::whereHas('purchase', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->latest()
            ->get();

        return view('items.mypage', compact('user', 'tab', 'sellItems', 'buyItems'));
    }

    /**
     * プロフィール編集画面を表示
     */
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();

        return view('items.profile', compact('user'));
    }

    /**
     * プロフィールを更新する
     */
    public function update(ProfileRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // 更新データを先に配列で用意する
        $updateData = [
            'name' => $request->input('name'),
            'postcode' => $request->input('postcode'),
            'address' => $request->input('address'),
            'building' => $request->input('building'),
        ];

        // 画像が選択されている場合は storage/app/public/profiles に保存
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profiles', 'public');

            // 保存した画像パスを更新対象に含める
            $updateData['image_path'] = $imagePath;
        }

        $user->update($updateData);

        return redirect('/mypage/profile');
    }
}
