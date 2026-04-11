<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログイン済みユーザーはコメントを送信できる
     */
    public function test_authenticated_user_can_post_comment(): void
    {
        /** @var \App\Models\User $loginUser */
        $loginUser = User::factory()->create();

        /** @var \App\Models\User $seller */
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => 'コメント対象商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 1000,
            'condition' => '良好',
            'image_path' => 'items/comment-item.jpg',
        ]);

        $response = $this->actingAs($loginUser)
            ->from('/item/' . $item->id)
            ->post('/item/' . $item->id . '/comment', [
                'content' => 'これはテストコメントです',
            ]);

        $response->assertRedirect('/item/' . $item->id);

        $this->assertDatabaseHas('comments', [
            'user_id' => $loginUser->id,
            'item_id' => $item->id,
            'content' => 'これはテストコメントです',
        ]);
    }

    /**
     * ログイン前のユーザーはコメントを送信できない
     */
    public function test_guest_user_cannot_post_comment(): void
    {
        /** @var \App\Models\User $seller */
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => 'ゲストコメント対象商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 2000,
            'condition' => '良好',
            'image_path' => 'items/guest-comment-item.jpg',
        ]);

        $response = $this->post('/item/' . $item->id . '/comment', [
            'content' => 'ゲストコメント',
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'content' => 'ゲストコメント',
        ]);
    }

    /**
     * コメントが未入力の場合、バリデーションメッセージが表示される
     */
    public function test_comment_is_required(): void
    {
        /** @var \App\Models\User $loginUser */
        $loginUser = User::factory()->create();

        /** @var \App\Models\User $seller */
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '未入力コメント対象商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 3000,
            'condition' => '良好',
            'image_path' => 'items/empty-comment-item.jpg',
        ]);

        $response = $this->actingAs($loginUser)
            ->from('/item/' . $item->id)
            ->post('/item/' . $item->id . '/comment', [
                'content' => '',
            ]);

        $response->assertRedirect('/item/' . $item->id);
        $response->assertSessionHasErrors([
            'content' => '商品コメントを入力してください',
        ]);
    }

    /**
     * コメントが255字を超える場合、バリデーションメッセージが表示される
     */
    public function test_comment_must_be_within_255_characters(): void
    {
        /** @var \App\Models\User $loginUser */
        $loginUser = User::factory()->create();

        /** @var \App\Models\User $seller */
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '文字数超過コメント対象商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 4000,
            'condition' => '良好',
            'image_path' => 'items/long-comment-item.jpg',
        ]);

        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($loginUser)
            ->from('/item/' . $item->id)
            ->post('/item/' . $item->id . '/comment', [
                'content' => $longComment,
            ]);

        $response->assertRedirect('/item/' . $item->id);
        $response->assertSessionHasErrors([
            'content' => '商品コメントは255文字以内で入力してください',
        ]);
    }
}
