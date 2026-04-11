<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 送付先住所変更画面で登録した住所が商品購入画面に反映される
     */
    public function test_updated_address_is_reflected_on_purchase_page(): void
    {
        /** @var \App\Models\User $buyer */
        $buyer = User::factory()->create([
            'email_verified_at' => now(),
            'postcode' => '111-1111',
            'address' => '東京都新宿区1-1-1',
            'building' => '元住所ビル',
        ]);

        /** @var \App\Models\User $seller */
        $seller = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '住所変更確認商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 5000,
            'condition' => '良好',
            'image_path' => 'items/address-item.jpg',
        ]);

        $this->actingAs($buyer)->post('/purchase/address/' . $item->id, [
            'postcode' => '222-2222',
            'address' => '東京都渋谷区2-2-2',
            'building' => '変更後ビル',
        ]);

        $response = $this->actingAs($buyer)->get('/purchase/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('222-2222');
        $response->assertSee('東京都渋谷区2-2-2');
        $response->assertSee('変更後ビル');
    }

    /**
     * 購入した商品に送付先住所が紐づいて登録される
     */
    public function test_purchase_is_saved_with_updated_address(): void
    {
        /** @var \App\Models\User $buyer */
        $buyer = User::factory()->create([
            'email_verified_at' => now(),
            'postcode' => '111-1111',
            'address' => '東京都新宿区1-1-1',
            'building' => '元住所ビル',
        ]);

        /** @var \App\Models\User $seller */
        $seller = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '購入住所保存商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 6000,
            'condition' => '良好',
            'image_path' => 'items/address-save-item.jpg',
        ]);

        $this->actingAs($buyer)->post('/purchase/address/' . $item->id, [
            'postcode' => '333-3333',
            'address' => '東京都港区3-3-3',
            'building' => '保存先ビル',
        ]);

        $response = $this->actingAs($buyer)->post('/purchase/' . $item->id, [
            'payment_method' => 'card',
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postcode' => '333-3333',
            'address' => '東京都港区3-3-3',
            'building' => '保存先ビル',
        ]);
    }
}
