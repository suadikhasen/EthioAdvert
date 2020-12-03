<?php

use App\LevelOfChaannel;
use Illuminate\Database\Seeder;
use App\TelegramPost;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // factory(\App\Paid::class,50)->create();
        // factory(TelegramPost::class,5)->create();
        factory(LevelOfChaannel::class,5);
    }
}
