<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'=> 'admin',
            'surname' => 'adminson',
            'email' => 'admin@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$s81GeKgjaqqdUWCi24t9a.28CRlJz1nBI6qf3FVv8a5ohjT0rWAlG', // admin123
            'remember_token' => Str::random(10)
        ]);

        User::factory(10)->create();

        DB::table('friends')->insert([
            ['user_id' => 1, 'friend_id' => 2, 'status' => 'approved'],
            ['user_id' => 2, 'friend_id' => 5, 'status' => 'approved'],
            ['user_id' => 5, 'friend_id' => 6, 'status' => 'approved'],
            ['user_id' => 1, 'friend_id' => 3, 'status' => 'approved'],
            ['user_id' => 1, 'friend_id' => 9, 'status' => 'approved'],
            ['user_id' => 9, 'friend_id' => 9, 'status' => 'approved'],
            ['user_id' => 6, 'friend_id' => 1, 'status' => 'approved'],
            ['user_id' => 5, 'friend_id' => 1, 'status' => 'approved']
        ]);
    }
}
