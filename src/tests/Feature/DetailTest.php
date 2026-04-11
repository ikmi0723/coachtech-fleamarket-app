<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品詳細ページに必要な情報が表示される
     */
    public function test_item_detail_page_displays_required_information(): void
    {
        /** @var \App\Models\User $seller */
        $seller = User::factory()->create([
            'name' => '出品者ユーザー',
        ]);

        /** @var \App\Models\User $commentUser */
        $commentUser = User::factory()->create([
            'name' => 'コメントユーザー',
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'これはテスト用の商品説明です',
            'price' => 5000,
            'condition' => '良好',
            'image_path' => 'items/test-item.jpg',
        ]);

        $category = Category::create([
            'name' => 'ファッション',
        ]);

        $item->categories()->attach($category->id);

        Like::create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
        ]);

        Comment::create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
            'content' => 'これはテストコメントです',
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);

        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('5,000');
        $response->assertSee('これはテスト用の商品説明です');
        $response->assertSee('ファッション');
        $response->assertSee('良好');
        $response->assertSee('コメントユーザー');
        $response->assertSee('これはテストコメントです');

        // いいね数 1、コメント数 1 が表示されることを確認
        $response->assertSee('1');
    }

    /**
     * 複数選択されたカテゴリが商品詳細ページに表示される
     */
    public function test_multiple_categories_are_displayed_on_item_detail_page(): void
    {
        /** @var \App\Models\User $seller */
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => 'カテゴリ確認商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 3000,
            'condition' => '良好',
            'image_path' => 'items/category-item.jpg',
        ]);

        $category1 = Category::create([
            'name' => 'メンズ',
        ]);

        $category2 = Category::create([
            'name' => 'ファッション',
        ]);

        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('メンズ');
        $response->assertSee('ファッション');
    }
}
