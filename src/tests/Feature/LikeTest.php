<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * いいねアイコンを押すと、いいねした商品として登録される
     */
    public function test_user_can_like_an_item(): void
    {
        /** @var \App\Models\User $loginUser */
        $loginUser = User::factory()->create();

        /** @var \App\Models\User $seller */
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => 'いいね対象商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 1000,
            'condition' => '良好',
            'image_path' => 'items/like-item.jpg',
        ]);

        $response = $this->actingAs($loginUser)
            ->from('/item/' . $item->id)
            ->post('/item/' . $item->id . '/like');

        $response->assertRedirect('/item/' . $item->id);

        $this->assertDatabaseHas('likes', [
            'user_id' => $loginUser->id,
            'item_id' => $item->id,
        ]);
    }

    /**
     * 追加済みのアイコンは色が変化する
     */
    public function test_liked_icon_is_displayed_as_active(): void
    {
        /** @var \App\Models\User $loginUser */
        $loginUser = User::factory()->create();

        /** @var \App\Models\User $seller */
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => 'いいね済み商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 2000,
            'condition' => '良好',
            'image_path' => 'items/liked-item.jpg',
        ]);

        Like::create([
            'user_id' => $loginUser->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($loginUser)->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('icon-heart-active.png');
    }

    /**
     * 再度いいねアイコンを押すと、いいねを解除できる
     */
    public function test_user_can_unlike_an_item(): void
    {
        /** @var \App\Models\User $loginUser */
        $loginUser = User::factory()->create();

        /** @var \App\Models\User $seller */
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => 'いいね解除対象商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 3000,
            'condition' => '良好',
            'image_path' => 'items/unlike-item.jpg',
        ]);

        Like::create([
            'user_id' => $loginUser->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($loginUser)
            ->from('/item/' . $item->id)
            ->post('/item/' . $item->id . '/like');

        $response->assertRedirect('/item/' . $item->id);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $loginUser->id,
            'item_id' => $item->id,
        ]);
    }
}
