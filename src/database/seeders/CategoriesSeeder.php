<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'ファッション',
            '家電',
            '食品',
            'キッチン',
            '本',
            'コスメ',
            'スポーツ',
            '雑貨'
        ];

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}
