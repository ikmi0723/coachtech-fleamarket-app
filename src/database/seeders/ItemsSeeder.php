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
        $user = User::first();

        $item = Item::create([
            'user_id' => $user->id,
            'name' => '腕時計',
            'brand' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => 15000,
            'condition' => '良好',
            'image_path' => 'watch.jpg'
        ]);

        // 商品にカテゴリを紐づける
        $categoryIds = Category::whereIn('name', ['ファッション'])->pluck('id');
        $item->categories()->attach($categoryIds);
    }
}
