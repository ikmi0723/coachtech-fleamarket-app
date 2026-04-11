<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 購入処理が実行され、purchases テーブルに保存される
     */
    public function test_user_can_purchase_item(): void
    {
        /** @var \App\Models\User $buyer */
        $buyer = User::factory()->create([
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル',
        ]);

        /** @var \App\Models\User $seller */
        $seller = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '購入対象商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 5000,
            'condition' => '良好',
            'image_path' => 'items/purchase-item.jpg',
        ]);

        $response = $this->actingAs($buyer)->post('/purchase/' . $item->id, [
            'payment_method' => 'card',
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postcode' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル',
        ]);
    }

    /**
     * 購入した商品は商品一覧画面で Sold と表示される
     */
    public function test_purchased_item_is_displayed_as_sold_on_index_page(): void
    {
        /** @var \App\Models\User $buyer */
        $buyer = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        /** @var \App\Models\User $seller */
        $seller = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => 'Sold表示対象商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 6000,
            'condition' => '良好',
            'image_path' => 'items/sold-item.jpg',
        ]);

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postcode' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
        $response->assertSee('Sold表示対象商品');
    }

    /**
     * 購入した商品がプロフィールの購入した商品一覧に追加される
     */
    public function test_purchased_item_is_displayed_on_mypage_buy_tab(): void
    {
        /** @var \App\Models\User $buyer */
        $buyer = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        /** @var \App\Models\User $seller */
        $seller = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '購入一覧表示商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 7000,
            'condition' => '良好',
            'image_path' => 'items/buy-tab-item.jpg',
        ]);

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postcode' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル',
        ]);

        $response = $this->actingAs($buyer)->get('/mypage?tab=buy');

        $response->assertStatus(200);
        $response->assertSee('購入一覧表示商品');
    }
}
