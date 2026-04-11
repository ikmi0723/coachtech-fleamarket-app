<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * マイページに必要な情報が表示される
     */
    public function test_mypage_displays_profile_and_item_lists(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'name' => 'テストユーザー',
            'image_path' => 'profiles/test-user.jpg',
            'postcode' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル',
        ]);

        /** @var \App\Models\User $otherUser */
        $otherUser = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $sellItem = Item::create([
            'user_id' => $user->id,
            'name' => '出品商品A',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 1000,
            'condition' => '良好',
            'image_path' => 'items/sell-item.jpg',
        ]);

        $buyItem = Item::create([
            'user_id' => $otherUser->id,
            'name' => '購入商品B',
            'brand' => 'ブランドB',
            'description' => '説明B',
            'price' => 2000,
            'condition' => '良好',
            'image_path' => 'items/buy-item.jpg',
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $buyItem->id,
            'payment_method' => 'card',
            'postcode' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル',
        ]);

        // 出品した商品タブ
        $sellResponse = $this->actingAs($user)->get('/mypage?tab=sell');

        $sellResponse->assertStatus(200);
        $sellResponse->assertSee('テストユーザー');
        $sellResponse->assertSee('profiles/test-user.jpg');
        $sellResponse->assertSee('出品商品A');
        $sellResponse->assertDontSee('購入商品B');

        // 購入した商品タブ
        $buyResponse = $this->actingAs($user)->get('/mypage?tab=buy');

        $buyResponse->assertStatus(200);
        $buyResponse->assertSee('購入商品B');
        $buyResponse->assertDontSee('出品商品A');
    }

    /**
     * プロフィール編集画面に過去設定値が初期値として表示される
     */
    public function test_profile_edit_page_displays_initial_values(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'name' => 'プロフィール確認ユーザー',
            'image_path' => 'profiles/profile-check.jpg',
            'postcode' => '987-6543',
            'address' => '東京都港区9-9-9',
            'building' => 'プロフィールビル',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('プロフィール確認ユーザー');
        $response->assertSee('987-6543');
        $response->assertSee('東京都港区9-9-9');
        $response->assertSee('profiles/profile-check.jpg');
    }
}
