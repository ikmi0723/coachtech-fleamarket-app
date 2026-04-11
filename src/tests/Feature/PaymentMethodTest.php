<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 支払い方法にカード支払いを選んだ場合、小計画面に反映される
     */
    public function test_selected_card_payment_method_is_reflected_on_purchase_page(): void
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
            'name' => '支払い方法確認商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 3000,
            'condition' => '良好',
            'image_path' => 'items/payment-method-item.jpg',
        ]);

        $response = $this->actingAs($buyer)
            ->withSession([
                '_old_input' => [
                    'payment_method' => 'card',
                ],
            ])
            ->get('/purchase/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('カード支払い');
    }

    /**
     * 支払い方法にコンビニ支払いを選んだ場合、小計画面に反映される
     */
    public function test_selected_convenience_payment_method_is_reflected_on_purchase_page(): void
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
            'name' => 'コンビニ支払い確認商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 4000,
            'condition' => '良好',
            'image_path' => 'items/convenience-payment-item.jpg',
        ]);

        $response = $this->actingAs($buyer)
            ->withSession([
                '_old_input' => [
                    'payment_method' => 'convenience',
                ],
            ])
            ->get('/purchase/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('コンビニ支払い');
    }
}
