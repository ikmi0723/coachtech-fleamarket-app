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
            'image_path' => 'watch.jpg',
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
            'image_path' => 'hdd.jpg',
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
            'image_path' => 'onion.jpg',
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
            'image_path' => 'laptop.jpg',
        ]);

        $item4CategoryIds = Category::whereIn('name', ['家電'])->pluck('id');
        $item4->categories()->attach($item4CategoryIds);
    }
}
