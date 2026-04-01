<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentsSeeder extends Seeder
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

        $watch = Item::where('name', '腕時計')->first();

        Comment::create([
            'user_id' => $user1->id,
            'item_id' => $watch->id,
            'content' => 'とても気になる商品です。',
        ]);

        Comment::create([
            'user_id' => $user2->id,
            'item_id' => $watch->id,
            'content' => '状態を詳しく知りたいです。',
        ]);
    }
}
