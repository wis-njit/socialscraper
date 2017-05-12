<?php

use Illuminate\Database\Seeder;
use App\Provider;

class TestProviderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $providers = ['google', 'facebook', 'twitter', 'instagram'];

        foreach ($providers as $provider) {
            Provider::firstOrCreate(['name' => $provider,]);
        }
    }

}
