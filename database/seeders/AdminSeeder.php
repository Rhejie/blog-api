<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'User 2',
            'email' => 'user2@gmail.com',
            'password' => bcrypt(123456),
        ]);


        User::create([
            'name' => 'user 1',
            'email' => 'user1@gmail.com',
            'password' => bcrypt(123456),
        ]);
    }
}
