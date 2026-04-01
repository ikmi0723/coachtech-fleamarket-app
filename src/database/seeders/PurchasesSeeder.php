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

        $hdd = Item::where('name', 'HDD')->first();
        $mic = Item::where('name', 'マイク')->first();

        // HDD を購入済みにする
        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $hdd->id,
            'payment_method' => 'card',
            'postcode' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        // マイク を購入済みにする
        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $mic->id,
            'payment_method' => 'convenience',
            'postcode' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);
    }
}
