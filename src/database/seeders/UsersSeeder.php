<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 商品出品者その1
        User::create([
            'name' => 'テストユーザー1',
            'email' => 'user1@example.com',
            'password' => Hash::make('password123'),
        ]);

        // 商品出品者その2
        User::create([
            'name' => 'テストユーザー2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
