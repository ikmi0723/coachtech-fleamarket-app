<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 全商品を取得できる
     */
    public function test_all_items_are_displayed_on_index_page(): void
    {
        $seller1 = User::factory()->create();
        $seller2 = User::factory()->create();

        $item1 = Item::create([
            'user_id' => $seller1->id,
            'name' => '商品A',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 1000,
            'condition' => '良好',
            'image_path' => 'items/item-a.jpg',
        ]);

        $item2 = Item::create([
            'user_id' => $seller2->id,
            'name' => '商品B',
            'brand' => 'ブランドB',
            'description' => '説明B',
            'price' => 2000,
            'condition' => '良好',
            'image_path' => 'items/item-b.jpg',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('商品A');
        $response->assertSee('商品B');
    }

    /**
     * 購入済み商品は Sold と表示される
     */
    public function test_sold_label_is_displayed_for_purchased_items(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '購入済み商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 3000,
            'condition' => '良好',
            'image_path' => 'items/item-sold.jpg',
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
        $response->assertSee('購入済み商品');
        $response->assertSee('Sold');
    }

    /**
     * 自分が出品した商品は一覧に表示されない
     */
    public function test_user_cannot_see_own_items_on_index_page(): void
    {
        $loginUser = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownItem = Item::create([
            'user_id' => $loginUser->id,
            'name' => '自分の商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 4000,
            'condition' => '良好',
            'image_path' => 'items/my-item.jpg',
        ]);

        $otherItem = Item::create([
            'user_id' => $otherUser->id,
            'name' => '他人の商品',
            'brand' => 'ブランドB',
            'description' => '説明B',
            'price' => 5000,
            'condition' => '良好',
            'image_path' => 'items/other-item.jpg',
        ]);

        /** @var \App\Models\User $loginUser */
        $response = $this->actingAs($loginUser)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('自分の商品');
        $response->assertSee('他人の商品');
    }
}
