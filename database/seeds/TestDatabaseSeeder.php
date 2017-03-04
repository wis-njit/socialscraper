<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(TestProviderTableSeeder::class);
        $this->call(TestProviderUserProfileTableSeeder::class);
        $this->call(UsersTableSeeder::class);

        Model::reguard();
    }
}
