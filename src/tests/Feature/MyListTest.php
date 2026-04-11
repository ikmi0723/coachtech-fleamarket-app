<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * いいねした商品だけが表示される
     */
    public function test_only_liked_items_are_displayed_on_mylist(): void
    {
        /** @var \App\Models\User $loginUser */
        $loginUser = User::factory()->create();

        /** @var \App\Models\User $seller */
        $seller = User::factory()->create();

        $likedItem = Item::create([
            'user_id' => $seller->id,
            'name' => 'いいねした商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 1000,
            'condition' => '良好',
            'image_path' => 'items/liked-item.jpg',
        ]);

        $notLikedItem = Item::create([
            'user_id' => $seller->id,
            'name' => 'いいねしていない商品',
            'brand' => 'ブランドB',
            'description' => '説明B',
            'price' => 2000,
            'condition' => '良好',
            'image_path' => 'items/not-liked-item.jpg',
        ]);

        Like::create([
            'user_id' => $loginUser->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($loginUser)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }

    /**
     * 購入済み商品は Sold と表示される
     */
    public function test_sold_label_is_displayed_for_purchased_items_on_mylist(): void
    {
        /** @var \App\Models\User $loginUser */
        $loginUser = User::factory()->create();

        /** @var \App\Models\User $seller */
        $seller = User::factory()->create();

        $soldItem = Item::create([
            'user_id' => $seller->id,
            'name' => '購入済みのいいね商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 3000,
            'condition' => '良好',
            'image_path' => 'items/sold-liked-item.jpg',
        ]);

        Like::create([
            'user_id' => $loginUser->id,
            'item_id' => $soldItem->id,
        ]);

        Purchase::create([
            'user_id' => $loginUser->id,
            'item_id' => $soldItem->id,
            'payment_method' => 'card',
            'postcode' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル',
        ]);

        $response = $this->actingAs($loginUser)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('購入済みのいいね商品');
        $response->assertSee('Sold');
    }

    /**
     * 未認証の場合は何も表示されない
     */
    public function test_guest_user_cannot_see_items_on_mylist(): void
    {
        /** @var \App\Models\User $seller */
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => 'ゲストに見えない商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 4000,
            'condition' => '良好',
            'image_path' => 'items/guest-hidden-item.jpg',
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('ゲストに見えない商品');
    }
}
