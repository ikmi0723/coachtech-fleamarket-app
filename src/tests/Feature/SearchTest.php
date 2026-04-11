<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品名で部分一致検索ができる
     */
    public function test_items_can_be_searched_by_partial_name(): void
    {
        /** @var \App\Models\User $seller */
        $seller = User::factory()->create();

        Item::create([
            'user_id' => $seller->id,
            'name' => '赤いバッグ',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 1000,
            'condition' => '良好',
            'image_path' => 'items/red-bag.jpg',
        ]);

        Item::create([
            'user_id' => $seller->id,
            'name' => '青い靴',
            'brand' => 'ブランドB',
            'description' => '説明B',
            'price' => 2000,
            'condition' => '良好',
            'image_path' => 'items/blue-shoes.jpg',
        ]);

        $response = $this->get('/?keyword=バッグ');

        $response->assertStatus(200);
        $response->assertSee('赤いバッグ');
        $response->assertDontSee('青い靴');
    }

    /**
     * 検索状態がマイリストでも保持されている
     */
    public function test_search_keyword_is_preserved_on_mylist_tab(): void
    {
        /** @var \App\Models\User $loginUser */
        $loginUser = User::factory()->create();

        /** @var \App\Models\User $seller */
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '黒いバッグ',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 3000,
            'condition' => '良好',
            'image_path' => 'items/black-bag.jpg',
        ]);

        Like::create([
            'user_id' => $loginUser->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($loginUser)->get('/?tab=mylist&keyword=バッグ');

        $response->assertStatus(200);
        $response->assertSee('value="バッグ"', false);
    }
}
