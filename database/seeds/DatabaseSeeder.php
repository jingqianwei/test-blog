<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 数据填充
        //$this->call(PostsTableSeeder::class);
        $this->call(CommentTableSeeder::class);
    }
}
