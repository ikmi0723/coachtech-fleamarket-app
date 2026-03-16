<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Seeder;

class PurchasesSeeder extends Seeder
{
    public function run()
    {
        $buyer = User::where('email', 'user1@example.com')->first();
        $item = Item::where('name', 'HDD')->first();

        // HDD を購入済みにして Sold 表示確認用に使う
        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postcode' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);
    }
}
