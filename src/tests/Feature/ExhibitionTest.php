<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExhibitionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品出品画面で入力した情報が正しく保存される
     */
    public function test_user_can_exhibit_item_with_valid_data(): void
    {
        Storage::fake('public');

        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $category1 = Category::create([
            'name' => 'ファッション',
        ]);

        $category2 = Category::create([
            'name' => 'メンズ',
        ]);

        $image = UploadedFile::fake()->create('test-item.jpeg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->post('/sell', [
            'image' => $image,
            'name' => '出品テスト商品',
            'brand' => 'テストブランド',
            'description' => 'これは出品テスト用の商品説明です',
            'categories' => [$category1->id, $category2->id],
            'condition' => '良好',
            'price' => 5000,
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => '出品テスト商品',
            'brand' => 'テストブランド',
            'description' => 'これは出品テスト用の商品説明です',
            'price' => 5000,
            'condition' => '良好',
        ]);

        $item = Item::where('name', '出品テスト商品')->first();

        $this->assertNotNull($item);

        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $category1->id,
        ]);

        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $category2->id,
        ]);

        $this->assertTrue(Storage::disk('public')->exists($item->image_path));
    }
}
