<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
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

        $user->update([
            'name' => $request->input('name'),
            'postcode' => $request->input('postcode'),
            'address' => $request->input('address'),
            'building' => $request->input('building'),
        ]);

        return redirect('/mypage/profile');
    }
}
