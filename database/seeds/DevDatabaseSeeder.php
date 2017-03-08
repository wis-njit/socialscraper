<?php

use Illuminate\Database\Seeder;

class DevDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(VoyagerDatabaseSeeder::class);
        $this->call(VoyagerDummyDatabaseSeeder::class);
        $this->call(ProviderTableSeeder::class);
        $this->call(ProviderUserProfileTableSeeder::class);
    }
}
