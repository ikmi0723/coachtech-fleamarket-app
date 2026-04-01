<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Seeder;

class LikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::where('email', 'user1@example.com')->first();

        $watch = Item::where('name', '腕時計')->first();
        $bag = Item::where('name', 'ショルダーバッグ')->first();

        Like::create([
            'user_id' => $user1->id,
            'item_id' => $watch->id,
        ]);

        Like::create([
            'user_id' => $user1->id,
            'item_id' => $bag->id,
        ]);
    }
}
