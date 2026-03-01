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
            '本',
            'コスメ',
            'スポーツ'
        ];

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}
