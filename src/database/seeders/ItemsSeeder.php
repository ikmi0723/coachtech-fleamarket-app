<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;


class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::where('email', 'user1@example.com')->first();
        $user2 = User::where('email', 'user2@example.com')->first();

        // 商品1
        /** @var \App\Models\Item $item1 */
        $item1 = Item::create([
            'user_id' => $user1->id,
            'name' => '腕時計',
            'brand' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => 15000,
            'condition' => '良好',
            'image_path' => 'items/watch.jpg',
        ]);

        $item1CategoryIds = Category::whereIn('name', ['ファッション'])->pluck('id');
        $item1->categories()->attach($item1CategoryIds);

        // 商品2
        /** @var \App\Models\Item $item2 */
        $item2 = Item::create([
            'user_id' => $user2->id,
            'name' => 'HDD',
            'brand' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'price' => 5000,
            'condition' => '目立った傷や汚れなし',
            'image_path' => 'items/hdd.jpg',
        ]);

        $item2CategoryIds = Category::whereIn('name', ['家電'])->pluck('id');
        $item2->categories()->attach($item2CategoryIds);

        // 商品3
        /** @var \App\Models\Item $item3 */
        $item3 = Item::create([
            'user_id' => $user1->id,
            'name' => '玉ねぎ3束',
            'brand' => 'なし',
            'description' => '新鮮な玉ねぎ3束のセット',
            'price' => 300,
            'condition' => 'やや傷や汚れあり',
            'image_path' => 'items/onion.jpg',
        ]);

        $item3CategoryIds = Category::whereIn('name', ['食品'])->pluck('id');
        $item3->categories()->attach($item3CategoryIds);

        // 商品4
        /** @var \App\Models\Item $item4 */
        $item4 = Item::create([
            'user_id' => $user2->id,
            'name' => 'ノートPC',
            'brand' => 'なし',
            'description' => '高性能なノートパソコン',
            'price' => 45000,
            'condition' => '良好',
            'image_path' => 'items/laptop.jpg',
        ]);

        $item4CategoryIds = Category::whereIn('name', ['家電'])->pluck('id');
        $item4->categories()->attach($item4CategoryIds);

        // 商品5
        /** @var \App\Models\Item $item5 */
        $item5 = Item::create([
            'user_id' => $user1->id,
            'name' => '革靴',
            'brand' => null,
            'description' => 'クラシックなデザインの革靴',
            'price' => 4000,
            'condition' => '状態が悪い',
            'image_path' => 'items/shoes.jpg',
        ]);

        $item4->categories()->attach(Category::whereIn('name', ['ファッション'])->pluck('id'));

        // 商品6
        /** @var \App\Models\Item $item6 */
        $item6 = Item::create([
            'user_id' => $user2->id,
            'name' => 'マイク',
            'brand' => 'なし',
            'description' => '高音質のレコーディング用マイク',
            'price' => 8000,
            'condition' => '目立った傷や汚れなし',
            'image_path' => 'items/mic.jpg',
        ]);
        $item6->categories()->attach(Category::whereIn('name', ['家電'])->pluck('id'));

        // 商品7
        /** @var \App\Models\Item $item7 */
        $item7 = Item::create([
            'user_id' => $user1->id,
            'name' => 'ショルダーバッグ',
            'brand' => null,
            'description' => 'おしゃれなショルダーバッグ',
            'price' => 3500,
            'condition' => 'やや傷や汚れあり',
            'image_path' => 'items/bag.jpg',
        ]);
        $item7->categories()->attach(Category::whereIn('name', ['ファッション'])->pluck('id'));

        // 商品8
        /** @var \App\Models\Item $item8 */
        $item8 = Item::create([
            'user_id' => $user2->id,
            'name' => 'タンブラー',
            'brand' => 'なし',
            'description' => '使いやすいタンブラー',
            'price' => 500,
            'condition' => '状態が悪い',
            'image_path' => 'items/tumbler.jpg',
        ]);
        $item8->categories()->attach(Category::whereIn('name', ['キッチン', '雑貨'])->pluck('id'));

        // 商品9
        /** @var \App\Models\Item $item9 */
        $item9 = Item::create([
            'user_id' => $user1->id,
            'name' => 'コーヒーミル',
            'brand' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'price' => 4000,
            'condition' => '良好',
            'image_path' => 'items/mill.jpg',
        ]);
        $item9->categories()->attach(Category::whereIn('name', ['キッチン'])->pluck('id'));

        // 商品10
        /** @var \App\Models\Item $item10 */
        $item10 = Item::create([
            'user_id' => $user2->id,
            'name' => 'メイクセット',
            'brand' => null,
            'description' => '便利なメイクアップセット',
            'price' => 2500,
            'condition' => '目立った傷や汚れなし',
            'image_path' => 'items/makeup.jpg',
        ]);
        $item10->categories()->attach(Category::whereIn('name', ['コスメ'])->pluck('id'));
    }
}
